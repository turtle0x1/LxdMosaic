// IMPORT CORE COMPONENTS
import Environment from './services/environment.service.js';
import Filesystem from './services/filesystem.service.js';
import Express from './services/express.service.js';

// IMPORT MIDDLEWARE
import AuthenticateExpressRoute from './middleware/expressAuth.middleware.js';

// IMPORT MODELS
import FetchHosts from './models/fetchHosts.model.js';
import WsTokens from './models/wsTokens.model.js';
import AllowedProjects from './models/allowedProjects.model.js';

// IMPORT SERVICES
import Hosts from './services/hosts.service.js';
import HostEvents from './services/hostEvents.service.js';
import Terminals from './services/terminals.service.js';
import VgaTerminals from './services/vgaTerminals.service.js';
import DbConnection from './services/db.service.js';

// IMPORT CONTROLLERS
import TextTerminalController from './controllers/textTerminal.controller.js';
import CloudConfigController from './controllers/cloudConfig.controller.js';
import VgaTerminalController from './controllers/vgaTerminal.controller.js';
import HostEventsController from './controllers/hostEvents.controller.js';

// LOAD ENVIRONMENT
new Environment().load(new URL('../.env', import.meta.url).pathname);

// INSTANTIATE FILESYSTEM
const fileSystem = new Filesystem();

// BUILD EXPRESS APP
const app = new Express(fileSystem).createExpressApp();

// INSTANTIATE DB CONNECTION
const con = new DbConnection(fileSystem).getDbConnection();

// BUILD MODELS
const allowedProjects = new AllowedProjects(con);
const wsTokens = new WsTokens(con);
const fetchHosts = new FetchHosts(con);

// BUILD SERVICES
const hosts = new Hosts(fetchHosts);
const hostEvents = new HostEvents(hosts, allowedProjects);
const terminals = new Terminals(hosts);
const vgaTerminals = new VgaTerminals(hosts);

// BUILD CONTROLLERS
const textTerminalController = new TextTerminalController(terminals);
const cloudConfgController = new CloudConfigController(terminals);
const vgaTerminalsController = new VgaTerminalController(vgaTerminals);
const hostEventsController = new HostEventsController(hostEvents);

// BUILD MIDDLEWARE
const authenticateExpressRoute = new AuthenticateExpressRoute(wsTokens, allowedProjects);

// REGISTER MIDDLEWARE
app.use(authenticateExpressRoute.authenticateReq);

// REGISTER HTTP ENDPOINTS
app.post('/terminals', textTerminalController.getNewTerminalProcess);

// REGISTER WEBSOCKET ENDPOINTS
app.ws('/node/terminal/', vgaTerminalsController.openTerminal);
app.ws('/node/operations', hostEventsController.addClientSocket);
app.ws('/node/console', textTerminalController.openTerminal);
app.ws('/node/cloudConfig', cloudConfgController.openTerminal);

// HANDLE EXIT
process.on('SIGINT', () => {
    hostEvents.closeAllSockets();
    terminals.closeAll();
    process.exit();
});
