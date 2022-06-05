// This originated from https://gist.github.com/CalebEverett/bed94582b437ffe88f650819d772b682
// and was modified to suite our needs
const fs = require('fs'),
  express = require('express'),
  http = require('http'),
  https = require('https'),
  expressWs = require('express-ws'),
  bodyParser = require('body-parser');
  path = require('path'),
  cors = require('cors'),
  rp = require('request-promise'),
  mysql = require('mysql'),
  sqlite3 = require('sqlite3').verbose(),
  Hosts = require('./Hosts'),
  WsTokens = require('./WsTokens'),
  HostEvents = require('./classes/HostEvents'),
  Terminals = require('./Terminals'),
  VgaTerminals = require("./VgaTerminals"),
  AllowedProjects = require("./classes/AllowedProjects"),
  hosts = null,
  hostEvents = null,
  terminals = null,
  wsTokens = null,
  vgaTerminals = null,
  allowedProjects = null;


var dotenv = require('dotenv')
var dotenvExpand = require('dotenv-expand')

var envImportResult = dotenv.config({
  path: __dirname + '/../.env',
});

dotenvExpand(envImportResult)

if (envImportResult.error) {
  throw envImportResult.error;
}


if(!fs.existsSync(process.env.CERT_PATH)){
    console.log("waiting 10 seconds to see if a certificate gets created");
}

var startDate = new Date();

while (!fs.existsSync(process.env.CERT_PATH)) {
    var seconds = (new Date().getTime() - startDate.getTime()) / 1000;
    if(seconds > 10){
        break;
    }
}

if(!fs.existsSync(process.env.CERT_PATH)){
    console.log("couldn't read certificate file");
    process.exit(1);
}

var usingSqllite = process.env.hasOwnProperty("DB_SQLITE") && process.env.DB_SQLITE !== "";

if(usingSqllite && !fs.existsSync(process.env.DB_SQLITE)){
    console.log("couldnt find db file file the response was {" + fs.existsSync(process.env.DB_SQLITE) + "} {" + process.env.DB_SQLITE + " }");
    if(process.env.hasOwnProperty("SNAP")){
        console.log("delaying restart 10 seconds to because we are in a snap");
        var startDate = new Date();
        while (true) {
            var seconds = (new Date().getTime() - startDate.getTime()) / 1000;
            if(seconds > 10){
                break;
            }
        }
    }
    process.exit(1);
}


// Https certificate and key file location for secure websockets + https server
var privateKey = fs.readFileSync(process.env.CERT_PRIVATE_KEY, 'utf8'),
  certificate = fs.readFileSync(process.env.CERT_PATH, 'utf8');
app = express();

app.use(cors());
app.use(bodyParser.json()); // to support JSON-encoded bodies
app.use(
  bodyParser.urlencoded({
    // to support URL-encoded bodies
    extended: true,
  })
);

var httpServer = http.createServer(app).listen(8001);
var httpsServer = https.createServer(
  {
    key: privateKey,
    cert: certificate,
  },
  app
);

expressWs(app, httpsServer)

//NOT authenticated because its not interesting but access may be required
app.get('/', function(req, res) {
  res.sendFile(path.join(__dirname + '/index.html'));
});

//NOT authenticated because its proxied by PHP which does auth
app.post('/terminals', function(req, res) {
  // Create a identifier for the console, this should allow multiple consolses
  // per user
  let uuid = terminals.getInternalUuid(req.body.host, req.body.container, req.query.cols, req.query.rows);
  res.json({ processId: uuid });
  res.send();
});

// DEPRECATED
app.post('/hosts/message/', function(req, res) {
  res.send({
    success: 'delivered',
  });
});

// DEPRECATED
app.post('/deploymentProgress/:deploymentId', function(req, res) {
  // Send an empty response
  res.send();
});


// Authenticate all access to node websockets
app.use(async (req, res, next)=>{
    if(req.path === "/" || req.path === ""){
        next()
    }

    let token = req.query.ws_token;
    let userId = req.query.user_id;
    let tokenIsValid = await wsTokens.isValid(token, userId);
    let canAccessProject = await allowedProjects.canAccessHostProject(userId, req.query.hostId, req.query.project)

    if(req.path === "/node/operations/.websocket"){
        // We dont use the project provided by the user in this route
        canAccessProject = true;
    }

    if (!tokenIsValid || !canAccessProject) {
        return next(new Error('authentication error'));
    }else{
        next();
    }
});

app.ws('/node/terminal/', (socket, req) => {
    vgaTerminals.openTerminal(socket, req);
})

app.ws('/node/operations', (socket, req) => {
    hostEvents.addClientSocket(req.query.user_id, socket)
})

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



if(!usingSqllite){
    var con = mysql.createConnection({
      host: process.env.DB_HOST,
      user: process.env.DB_USER,
      password: process.env.DB_PASS,
      database: process.env.DB_NAME,
    });

    con.connect(function(err) {
      if (err) {
          if(process.env.hasOwnProperty("SNAP")){
              console.log("delaying restart 10 seconds to because we are in a snap");
              var startDate = new Date();
              while (true) {
                  var seconds = (new Date().getTime() - startDate.getTime()) / 1000;
                  if(seconds > 10){
                      break;
                  }
              }
          }
          throw err;
      }
    });
}else{
    var con = new sqlite3.Database(process.env.DB_SQLITE);
}

hosts = new Hosts(con, fs, http, https);
allowedProjects = new AllowedProjects(con);
wsTokens = new WsTokens(con);
hostEvents = new HostEvents(hosts, allowedProjects);
terminals = new Terminals(http, https);
vgaTerminals = new VgaTerminals(http, https, hosts);

// Prevent races, just loads on init
hosts.loadHosts()

httpsServer.listen(3000, function() {});

process.on('SIGINT', function() {
  hostEvents.closeAllSockets();
  terminals.closeAll();
  process.exit();
});
