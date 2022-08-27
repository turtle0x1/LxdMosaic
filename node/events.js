// This originated from https://gist.github.com/CalebEverett/bed94582b437ffe88f650819d772b682
// and was modified to suite our needs
const fs = require('fs'),
  express = require('express'),
  http = require('http'),
  https = require('https'),
  expressWs = require('express-ws'),
  bodyParser = require('body-parser'),
  cors = require('cors'),
  dotenv = require('dotenv'),
  dotenvExpand = require('dotenv-expand'),
  AuthenticateExpressRoute = require('./middleware/expressAuth.middleware'),
  Hosts = require('./classes/Hosts'),
  WsTokens = require('./models/wsTokens.model'),
  HostEvents = require('./classes/HostEvents'),
  Terminals = require('./classes/Terminals'),
  VgaTerminals = require("./classes/VgaTerminals"),
  AllowedProjects = require("./models/allowedProjects.model"),
  DbConnection = require("./services/db.service.js"),
  Filesystem = require("./services/filesystem.service.js");

var envImportResult = dotenvExpand(dotenv.config({
  path: __dirname + '/../.env',
}))

if (envImportResult.error) {
  throw envImportResult.error;
}

var filesystem = new Filesystem();

if(!filesystem.checkAndAwaitFileExists(process.env.CERT_PATH)){
    console.log("Couldn't read HTTPS certificate file");
    process.exit(1);
}

var con = (new DbConnection(filesystem)).getDbConnection();
var hosts = new Hosts(con);
var allowedProjects = new AllowedProjects(con);
var wsTokens = new WsTokens(con);
var hostEvents = new HostEvents(hosts, allowedProjects);
var terminals = new Terminals();
var vgaTerminals = new VgaTerminals(hosts);

var authenticateExpressRoute = new AuthenticateExpressRoute(wsTokens, allowedProjects)

app = express();
app.use(cors());
app.use(bodyParser.json()); // to support JSON-encoded bodies
app.use(bodyParser.urlencoded({
    // to support URL-encoded bodies
    extended: true,
}));

var httpsServer = https.createServer({
    key:  fs.readFileSync(process.env.CERT_PRIVATE_KEY, 'utf8'),
    cert: fs.readFileSync(process.env.CERT_PATH, 'utf8'),
}, app);

expressWs(app, httpsServer)

// Authenticate all routes
app.use(authenticateExpressRoute.authenticateReq);

app.post('/terminals', function(req, res) {
  // Create a identifier for the console, this should allow multiple consolses
  // per user
  let uuid = terminals.getInternalUuid(req.body.host, req.body.container, req.query.cols, req.query.rows);
  res.json({ processId: uuid });
  res.send();
});

app.ws('/node/terminal/', vgaTerminals.openTerminal)
app.ws('/node/operations', hostEvents.addClientSocket)

app.ws('/node/console', (socket, req) => {
     let host = req.query.hostId,
         container = req.query.instance,
         uuid = req.query.pid,
         shell = req.query.shell,
         project = req.query.project;
  terminals
    .createTerminalIfReq(socket, hosts.getHosts(), host, project, container, uuid, shell)
    .then(() => {
      //NOTE When user inputs from browser
      socket.on("message", (msg) => {
        let resizeCommand = msg.match(/resize-window\:cols=([0-9]+)&rows=([0-9]+)/);
        if(resizeCommand){
            terminals.resize(uuid, resizeCommand[1], resizeCommand[2])
        }else{
            terminals.sendToTerminal(uuid, msg);
        }
      });
      socket.on('error', () => {
          console.log("socket error");
      });
      socket.on('close', () => {
          terminals.close(uuid);
      });
  }).catch(e => {
      socket.close();
  });
});

app.ws('/node/cloudConfig', (socket, req) => {
     let host = req.query.hostId,
         container = req.query.instance,
         uuid = terminals.getInternalUuid(req.body.host, req.body.container, req.query.cols, req.query.rows),
         shell = req.query.shell,
         project = req.query.project;

     let dangerRegexs = [
         // A command not found in cloud-config `runcmd` array
         new RegExp('\/var\/lib\/cloud\/instance\/scripts\/runcmd:.* not found'),
         // Failed to install pacakges found in cloud-config `packages` array
         new RegExp('.*util.py\\[WARNING\\]: Failed to install packages:'),
         // Failed to run a generic cloud-config module
         new RegExp('.*cc_scripts_user.py\\[WARNING\\]: Failed to run module'),
         // Failed to run `packages` module
         new RegExp(".*cc_package_update_upgrade_install.py\\[WARNING\\]"),
         // Failed to read cloud-config data properly
         new RegExp(".*__init__.py\\[WARNING\\]: Unhandled non-multipart \\(text\\/x-not-multipart\\) userdata:.*")
     ];

     let messageCallbacks = {
      formatServerResponse: function(data){
        let x = data.split("\r\n")

        if(x[0].match("exit") || x[0] == "^C"){
            return ''
        }else if(data.match(".*until \\[ \\-f")){
            return '\033[34m' + data + '\033[0m'
        }

        let finishedLine = false;
        x.forEach((line, i) => {
            let isStartLine = line.match(".*until \\[ \\-f") !== null
            let isEndLine = line.match(/Cloud\-init.*finished/g) !== null
            if(isStartLine || isEndLine){
                if(isEndLine){
                    finishedLine = true;
                }
                // Make text blue
                x[i] = '\033[34m' + line + '\033[0m'
            }else{
                dangerRegexs.forEach(regex=>{
                    if(line.match(regex)){
                        x[i] = '\033[31m' + line + '\033[0m'
                    }
                });
            }
        });
        // If its the last line we want to remove the final "\n"
        if(finishedLine){
            x.splice((x.length - 1), 1);
        }
        return x.join("\r\n")
      },
      afterSeverResponeSent: function(data){
          // Check for the cloud-init finished message
          if(data.match(/Cloud\-init.*finished/g) !== null){
            // `ctrl + c` out the `tail -f` command and exit
            terminals.sendToTerminal(uuid, `\x03`)
            terminals.close(uuid);
            socket.close()
          }
      }
  }

  terminals
    .createTerminalIfReq(socket, hosts.getHosts(), host, project, container, uuid, shell, messageCallbacks)
    .then(() => {
      // Need to give the socket some time to establish before reading log file
      setTimeout(()=>{
          terminals.sendToTerminal(uuid, 'until [ -f /var/log/cloud-init-output.log ];\ do sleep 1;\ done && tail -f /var/log/cloud-init-output.log\r\n')
      }, 100)

      //NOTE Ignore all user to input anything to this console

      socket.on('close', () => {
          terminals.close(uuid);
      });
    })
    .catch((err) => {
        socket.close();
    });
});


// Prevent races, just loads on init
hosts.loadHosts()

httpsServer.listen(3000, function() {});

process.on('SIGINT', function() {
  hostEvents.closeAllSockets();
  terminals.closeAll();
  process.exit();
});
