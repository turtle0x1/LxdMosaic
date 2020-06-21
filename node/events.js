// This originated from https://gist.github.com/CalebEverett/bed94582b437ffe88f650819d772b682
// and was modified to suite our needs
const fs = require('fs'),
  express = require('express'),
  http = require('http'),
  https = require('https'),
  expressWs = require('express-ws'),
  path = require('path'),
  cors = require('cors'),
  rp = require('request-promise'),
  mysql = require('mysql'),
  Hosts = require('./Hosts');
  WsTokens = require('./WsTokens');
(HostOperations = require('./HostOperations')),
  (Terminals = require('./Terminals')),
  (bodyParser = require('body-parser')),
  (hosts = null),
  (hostOperations = null),
  (terminals = null),
  (wsTokens = null);

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

var io = require('socket.io')(httpsServer);

var operationSocket = io.of('/operations');

function createWebSockets() {
  hosts.loadHosts().then(hostDetails => {
    hostOperations.setupWebsockets(hostDetails, operationSocket);
  });
}

app.get('/hosts/reload/', function(req, res) {
  createWebSockets();
  res.send({
    success: 'reloaded',
  });
});

app.post('/hosts/message/', function(req, res) {
  operationSocket.emit(req.body.type, req.body.data);
  res.send({
    success: 'delivered',
  });
});

app.get('/', function(req, res) {
  res.sendFile(path.join(__dirname + '/index.html'));
});

app.post('/terminals', function(req, res) {
  // Create a identifier for the console, this should allow multiple consolses
  // per user
  uuid = terminals.getInternalUuid(req.body.host, req.body.container);
  res.json({ processId: uuid });
  res.send();
});

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

    operationSocket.emit('deploymentProgress', body);
  }
  // Send an empty response
  res.send();
});

var terminalsIo = io.of('/terminals');

terminalsIo.on('connect', function(socket) {
  let host = socket.handshake.query.hostId,
    container = socket.handshake.query.container,
    uuid = socket.handshake.query.pid,
    shell = socket.handshake.query.shell;

  terminals
    .createTerminalIfReq(socket, hosts.getHosts(), host, container, uuid, shell)
    .then(() => {
      //NOTE When user inputs from browser
      socket.on('data', function(msg) {
        terminals.sendToTerminal(uuid, msg);
      });

      socket.on('close', function(uuid) {
        terminals.close(uuid);
      });
    })
    .catch(() => {
      // Prevent the browser re-trying (this maybe can be changed later)
      socket.disconnect();
    });
});

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

io.use(async (socket, next) => {
  let token = socket.handshake.query.ws_token;
  let userId = socket.handshake.query.user_id;
  let isValid = await wsTokens.isValid(token, userId);

  if (isValid) {
    return next();
  }
  return next(new Error('authentication error'));
});

hosts = new Hosts(con, fs, rp);
wsTokens = new WsTokens(con);
hostOperations = new HostOperations(fs);
terminals = new Terminals(rp);

createWebSockets();
httpsServer.listen(3000, function() {});

process.on('SIGINT', function() {
  hostOperations.closeSockets();
  terminals.closeAll();
  process.exit();
});
