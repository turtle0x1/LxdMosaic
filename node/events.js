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
  HostOperations = require('./HostOperations'),
  Terminals = require('./Terminals'),
  VgaTerminals = require("./VgaTerminals"),
  hosts = null,
  hostOperations = null,
  terminals = null,
  wsTokens = null,
  vgaTerminals = null;


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

function createWebSockets() {
  hosts.loadHosts().then(hostDetails => {
    hostOperations.setupWebsockets(hostDetails);
  });
}

//NOT authenticated because its not interesting but access may be required
app.get('/', function(req, res) {
  res.sendFile(path.join(__dirname + '/index.html'));
});

//NOT authenticated because its proxied by PHP which does auth
app.post('/terminals', function(req, res) {
  // Create a identifier for the console, this should allow multiple consolses
  // per user
  uuid = terminals.getInternalUuid(req.body.host, req.body.container);
  res.json({ processId: uuid });
  res.send();
});

//NOT authenticated because its called by php
app.get('/hosts/reload/', function(req, res) {
  createWebSockets();
  res.send({
    success: 'reloaded',
  });
});

//NOT authenticated because its called by php
app.post('/hosts/message/', function(req, res) {
  hostOperations.sendToOpsClients(req.body.type, req.body.data);
  res.send({
    success: 'delivered',
  });
});

//NOT authenticated because its called by php
app.post('/deploymentProgress/:deploymentId', function(req, res) {
  let body = req.body;

  body.deploymentId = req.params.deploymentId;

  if (body.hasOwnProperty('hostname') !== true) {
    // https://stackoverflow.com/questions/3050518/what-http-status-response-code-should-i-use-if-the-request-is-missing-a-required
    res.statusMessage = 'Please provide host name in req body';
    res.status(422).end();
  } else {
    let d = {
      uri: 'https://lxd.local/api/Deployments/UpdatePhoneHomeController/update',
      form: {
        deploymentId: parseInt(body.deploymentId),
        hostname: body.hostname,
      },
      rejectUnauthorized: false,
    };

    // send to LXDMosaic the phone-home event, error checking ?
    rp(d)
      .then(() => {})
      .cactch(() => {});

    hostOperations.sendToOpsClients('deploymentProgress', body);
  }
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
    let isValid = await wsTokens.isValid(token, userId);

    if (!isValid) {
        return next(new Error('authentication error'));
    }else{
        next();
    }
});

app.ws('/node/terminal/:hostId/:project/:instance', (socket, req) => {
    vgaTerminals.openTerminal(socket, req);
})

app.ws('/node/operations', (socket, req) => {
    hostOperations.addClientSocket(socket)
})

app.ws('/node/console', (socket, req) => {
     let host = req.query.hostId,
         container = req.query.container,
         uuid = req.query.pid,
         shell = req.query.shell,
         project = req.query.project;

  terminals
    .createTerminalIfReq(socket, hosts.getHosts(), host, project, container, uuid, shell)
    .then(() => {
      //NOTE When user inputs from browser
      socket.on("message", (msg) => {
        terminals.sendToTerminal(uuid, msg);
      });

      socket.on('close', () => {
          terminals.close(uuid);
      });
    })
    .catch(() => {
      // Prevent the browser re-trying (this maybe can be changed later)
      socket.disconnect();
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

hosts = new Hosts(con, fs, rp);
wsTokens = new WsTokens(con);
hostOperations = new HostOperations(fs);
terminals = new Terminals(rp);
vgaTerminals = new VgaTerminals(rp, hosts);


httpsServer.listen(3000, function() {});

createWebSockets();
process.on('SIGINT', function() {
  hostOperations.closeSockets();
  terminals.closeAll();
  process.exit();
});
