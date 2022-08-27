    // REQUIRE CORE COMPONENTS
const Environment = require('./services/environment.service'),
    Filesystem = require('./services/filesystem.service'),
    Express = require('./services/express.service'),
    // REQUIRE MIDDLEWARE
    AuthenticateExpressRoute = require('./middleware/expressAuth.middleware'),
    // REQUIRE MODELS
    FetchHosts = require('./models/fetchHosts.model'),
    WsTokens = require('./models/wsTokens.model'),
    AllowedProjects = require('./models/allowedProjects.model'),
    // REQUIRE SERVICES
    Hosts = require('./services/hosts.service'),
    HostEvents = require('./services/hostEvents.service'),
    Terminals = require('./services/terminals.service'),
    VgaTerminals = require('./services/vgaTerminals.service'),
    DbConnection = require('./services/db.service'),
    // REQUIRE CONTROLLERS
    TextTerminalController = require('./controllers/textTerminal.controller'),
    CloudConfigController = require('./controllers/cloudConfig.controller');

// LOAD ENVIRONMENT
(new Environment).load(__dirname + '/../.env')

// INSTANTIATE FILESYSTEM
var fileSystem = new Filesystem();

// BUILD EXPRESS APP
var app = (new Express(fileSystem)).createExpressApp()

// INSTANTIATE DB CONNECTION
var con = (new DbConnection(fileSystem)).getDbConnection();

// BUILD MODELS
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
