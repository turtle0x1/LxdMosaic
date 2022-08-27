// This originated from https://gist.github.com/CalebEverett/bed94582b437ffe88f650819d772b682
// and was modified to suite our needs
const Environment = require ("./services/environment.service.js")
  Express = require ("./services/express.service.js")
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

(new Environment).load(__dirname + '/../.env')

var filesystem = new Filesystem();
var app = (new Express(filesystem)).createExpressApp()

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

process.on('SIGINT', function() {
  hostEvents.closeAllSockets();
  terminals.closeAll();
  process.exit();
});
