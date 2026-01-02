import express from 'express';
import https from 'https';
import expressWs from 'express-ws';
import bodyParser from 'body-parser';
import cors from 'cors';

export default class  Express {
    constructor(fileSystem){
        this._fileSystem = fileSystem
    }

    createExpressApp() {
        if(!this._fileSystem.checkAndAwaitFileExists(process.env.CERT_PATH)){
            throw "Couldn't read HTTPS certificate file";
        }

        let app = express();
        app.use(cors());
        app.use(bodyParser.json()); // to support JSON-encoded bodies
        app.use(bodyParser.urlencoded({
            // to support URL-encoded bodies
            extended: true,
        }));

        var httpsServer = https.createServer({
            key:  this._fileSystem.readFileSync(process.env.CERT_PRIVATE_KEY, 'utf8'),
            cert: this._fileSystem.readFileSync(process.env.CERT_PATH, 'utf8'),
        }, app);

        expressWs(app, httpsServer)
        httpsServer.listen(3000, function() {});
        return app;
    }
}
