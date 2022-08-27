// This originated from https://gist.github.com/CalebEverett/bed94582b437ffe88f650819d772b682
// and was modified to suite our needs
const Environment = require("./services/environment.service.js"),
    Filesystem = require("./services/filesystem.service.js"),
    Express = require("./services/express.service.js"),
    AuthenticateExpressRoute = require('./middleware/expressAuth.middleware'),
    Hosts = require('./services/hosts.service'),
    WsTokens = require('./models/wsTokens.model'),
    FetchHosts = require('./models/fetchHosts.model'),
    HostEvents = require('./services/hostEvents.service'),
    Terminals = require('./services/terminals.service'),
    VgaTerminals = require("./services/vgaTerminals.service"),
    AllowedProjects = require("./models/allowedProjects.model"),
    DbConnection = require("./services/db.service.js"),
    TextTerminalController = require("./controllers/textTerminal.controller.js"),
    CloudConfigController = require("./controllers/cloudConfig.controller.js");

// LOAD ENVIRONMENT
(new Environment).load(__dirname + '/../.env')

var filesystem = new Filesystem();

// BUILD EXPRESS APP
var app = (new Express(filesystem)).createExpressApp()

// BUILD MODELS
var con = (new DbConnection(filesystem)).getDbConnection();
var allowedProjects = new AllowedProjects(con);
var wsTokens = new WsTokens(con);
var fetchHosts = new FetchHosts(con);

// BUILD SERVICES
var hosts = new Hosts(fetchHosts);
var hostEvents = new HostEvents(hosts, allowedProjects);
var terminals = new Terminals(hosts);
var vgaTerminals = new VgaTerminals(hosts);

// BUILD CONTROLLERS
var textTerminalController = new TextTerminalController(terminals)
var cloudConfgController = new CloudConfigController(terminals)

// BUILD MIDDLEWARE
var authenticateExpressRoute = new AuthenticateExpressRoute(wsTokens, allowedProjects)

// REGISTER MIDDLEWARE
app.use(authenticateExpressRoute.authenticateReq);

// REGISTER HTTP ENDPOINTS
app.post('/terminals', textTerminalController.getNewTerminalProcess);

// REGISTER WEBSOCKET ENDPOINTS
app.ws('/node/terminal/', vgaTerminals.openTerminal)
app.ws('/node/operations', hostEvents.addClientSocket)
app.ws('/node/console', textTerminalController.openTerminal)
app.ws('/node/cloudConfig', cloudConfgController.openTerminal)

// HANDLE EXIT
process.on('SIGINT', function() {
    hostEvents.closeAllSockets();
    terminals.closeAll();
    process.exit();
});
