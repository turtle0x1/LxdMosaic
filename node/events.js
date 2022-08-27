// This originated from https://gist.github.com/CalebEverett/bed94582b437ffe88f650819d772b682
// and was modified to suite our needs
const express = require('express'),
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
  Filesystem = require("./services/filesystem.service.js"),
  TextTerminalController = require("./controllers/textTerminal.controller.js"),
  CloudConfigController = require("./controllers/cloudConfig.controller.js");

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

// SERVICES
var con = (new DbConnection(filesystem)).getDbConnection();
var hosts = new Hosts(con);
var allowedProjects = new AllowedProjects(con);
var wsTokens = new WsTokens(con);
var hostEvents = new HostEvents(hosts, allowedProjects);
var terminals = new Terminals();
var vgaTerminals = new VgaTerminals(hosts);

// CONTROLLERS
var textTerminalController = new TextTerminalController(hosts, terminals)
var cloudConfgController = new CloudConfigController(hosts, terminals)

// MIDDLEWARE
var authenticateExpressRoute = new AuthenticateExpressRoute(wsTokens, allowedProjects)

app = express();
app.use(cors());
app.use(bodyParser.json()); // to support JSON-encoded bodies
app.use(bodyParser.urlencoded({
    // to support URL-encoded bodies
    extended: true,
}));

var httpsServer = https.createServer({
    key:  filesystem.readFileSync(process.env.CERT_PRIVATE_KEY, 'utf8'),
    cert: filesystem.readFileSync(process.env.CERT_PATH, 'utf8'),
}, app);

expressWs(app, httpsServer)

// Authenticate all routes
app.use(authenticateExpressRoute.authenticateReq);

// Create a identifier for the console, this should allow multiple consolses
// per user
app.post('/terminals', textTerminalController.getNewTerminalProcess);

app.ws('/node/terminal/', vgaTerminals.openTerminal)
app.ws('/node/operations', hostEvents.addClientSocket)
app.ws('/node/console', textTerminalController.openTerminal)
app.ws('/node/cloudConfig', cloudConfgController.openTerminal)

// Prevent races, just loads on init
hosts.loadHosts()

httpsServer.listen(3000, function() {});

process.on('SIGINT', function() {
  hostEvents.closeAllSockets();
  terminals.closeAll();
  process.exit();
});
