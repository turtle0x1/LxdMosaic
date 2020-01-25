// This originated from https://gist.github.com/CalebEverett/bed94582b437ffe88f650819d772b682
// and was modified to suite our needs
const fs = require('fs'),
  WebSocket = require('ws'),
  express = require('express'),
  http = require('http'),
  https = require('https'),
  expressWs = require('express-ws'),
  path = require('path'),
  cors = require('cors'),
  uuidv1 = require('uuid/v1'),
  rp = require('request-promise'),
  mysql = require('mysql'),
  Hosts = require('./Hosts');
HostOperations = require('./HostOperations');

const envImportResult = require('dotenv').config({
  path: '/var/www/LxdMosaic/.env',
});

if (envImportResult.error) {
  throw envImportResult.error;
}

var con = mysql.createConnection({
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASS,
  database: process.env.DB_NAME,
});

// Https certificate and key file location for secure websockets + https server
var privateKey = fs.readFileSync(process.env.CERT_PRIVATE_KEY, 'utf8'),
  certificate = fs.readFileSync(process.env.CERT_PATH, 'utf8');
(certDir = '/var/www/LxdMosaic/src/sensitiveData/certs/'),
  (lxdConsoles = {}),
  (credentials = {
    key: privateKey,
    cert: certificate,
  }),
  (app = express());

app.use(cors());
var bodyParser = require('body-parser');
app.use(bodyParser.json()); // to support JSON-encoded bodies
app.use(
  bodyParser.urlencoded({
    // to support URL-encoded bodies
    extended: true,
  })
);

var httpServer = http.createServer(app).listen(8001);
var httpsServer = https.createServer(credentials, app);
var io = require('socket.io')(httpsServer);

var operationSocket = io.of('/operations');

hosts = new Hosts(con, fs, rp);
hostOperations = new HostOperations(fs);

function createExecOptions(host, container) {
  let hostDetails = hosts.getHosts();
  if (hostDetails == undefined) {
    return false;
  }
  let url = hostDetails[host].supportsVms ? 'instances' : 'containers';
  return {
    method: 'POST',
    uri: `https://${hostDetails[host].hostWithOutProtoOrPort}:${hostDetails[host].port}/1.0/${url}/${container}/exec`,
    cert: hostDetails[host].cert,
    key: hostDetails[host].key,
    rejectUnauthorized: false,
    json: true,
  };
}

const lxdExecBody = {
  command: ['bash'],
  environment: {
    HOME: '/root',
    TERM: 'xterm',
    USER: 'root',
  },
  'wait-for-websocket': true,
  interactive: true,
};

con.connect(function(err) {
  if (err) {
    throw err;
  }
});

function createWebSockets() {
  hosts.loadHosts().then(hostDetails => {
    hostOperations.setupWebsockets(hostDetails, operationSocket);
  });
}

httpsServer.listen(3000, function() {});

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

var terminalsIo = io.of('/terminals');

terminalsIo.on('connect', function(socket) {
  //TODO Map pid to lxd operation id so on-reconnects they connect back
  //     to the same socket to prevent lots of dead sockets
  let identifier = socket.handshake.query.pid;
  let shell = socket.handshake.query.shell;
  if (typeof shell == 'string' && shell !== '') {
    lxdExecBody.command = [shell];
  }

  if (lxdConsoles[identifier] == undefined) {
    let host = socket.handshake.query.hostId;
    let container = socket.handshake.query.container;

    let execOptions = createExecOptions(host, container);

    if (execOptions == false) {
      lxdConsoles[identifier].close();
    }

    const wsoptions = {
      cert: execOptions.cert,
      key: execOptions.key,
      rejectUnauthorized: false,
    };

    execOptions.body = lxdExecBody;

    rp(execOptions)
      .then(output => {
        if (output.hasOwnProperty('error') && output.error !== '') {
          socket.emit('data', 'Container Offline');
          return false;
        }

        let hostDetails = hosts.getHosts();

        let url = `wss://${hostDetails[host].hostWithOutProtoOrPort}:${hostDetails[host].port}`;

        const lxdWs = new WebSocket(
          url +
            output.operation +
            '/websocket?secret=' +
            output.metadata.metadata.fds['0'],
          wsoptions
        );

        lxdWs.on('error', error => console.log(error));

        lxdWs.on('message', data => {
          const buf = Buffer.from(data);
          data = buf.toString();
          socket.emit('data', data);
        });
        lxdConsoles[identifier] = lxdWs;
      })
      .catch(error => {
        // Until we work out what to do here
        throw error;
      });
  }

  //NOTE When user inputs from browser
  socket.on('data', function(msg) {
    if (lxdConsoles[identifier] == undefined) {
      return;
    }

    lxdConsoles[identifier].send(
      msg,
      {
        binary: true,
      },
      () => {}
    );
  });

  socket.on('close', function(identifier) {
    setTimeout(() => {
      if (lxdConsoles[identifier] == undefined) {
        return;
      }
      lxdConsoles[identifier].send('exit  \r', { binary: true }, function() {
        lxdConsoles[identifier].close();
        delete lxdConsoles[identifier];
      });
    }, 100);
  });
});

var terminalCache = {};

app.post('/terminals', function(req, res) {
  // Create a identifier for the console, this should allow multiple consolses
  // per user
  res.json({ processId: uuidv1() });
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

createWebSockets();

process.on('SIGINT', function() {
  hostOperations.closeSockets();

  let keys = Object.keys(lxdConsoles);

  for (let i = 0; i < keys.length; i++) {
    lxdConsoles[keys[i]].close();
  }
  process.exit();
});
