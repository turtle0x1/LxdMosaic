// This originated from https://gist.github.com/CalebEverett/bed94582b437ffe88f650819d772b682
// and was modified to suite our needs
const fs = require('fs'),
      WebSocket = require('ws'),
      express = require('express'),
      https = require('https'),
      mysql = require('mysql');

const envImportResult = require('dotenv').config({path: "/var/www/LxdManager/.env"});

if (envImportResult.error) {
  throw envImportResult.error
}

// Https certificate and key file location for secure websockets + https server
var privateKey  = fs.readFileSync('/etc/ssl/private/ssl-cert-snakeoil.key', 'utf8');
var certificate = fs.readFileSync('/etc/ssl/certs/ssl-cert-snakeoil.pem', 'utf8');
var certDir = "/var/www/LxdManager/src/sensitiveData/certs/";

var credentials = {key: privateKey, cert: certificate};
var app = express();
var httpsServer = https.createServer(credentials, app);
var io = require('socket.io')(httpsServer);

var con = mysql.createConnection({
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASS,
    database: process.env.DB_NAME
});

con.connect(function(err) {
    if (err) {
      throw err;
    }
});

function createWebSockets()
{
      con.query("SELECT * FROM Hosts", function (err, result, fields) {
        if (err) {
            throw err;
        }
        for(i = 0; i < result.length; i++){
            let lxdClientCert = certDir + result[i].Host_Cert_Only_File
            let lxdClientKey = certDir + result[i].Host_Key_File

            // Connecting to the lxd server/s
            const wsoptions = {
                cert: fs.readFileSync(lxdClientCert),
                key: fs.readFileSync(lxdClientKey),
                rejectUnauthorized: false,
            }

            var portRegex = /:[0-9]+/;

            let hostWithOutProto = result[i].Host_Url_And_Port.replace("https://", "");
            let hostWithOutProtoOrPort = hostWithOutProto.replace(portRegex, "");

            var ws = new WebSocket('wss://' + hostWithOutProto + '/1.0/events?type=operation', wsoptions);

            ws.on('error', (error) => {
              console.log(error)
            });

            ws.on('message', function(data, flags) {
              var buf = Buffer.from(data)
              let message = JSON.parse(data.toString());
              message.host = hostWithOutProtoOrPort;
              io.emit('operationUpdate', message);
            });
        }
      });
}


httpsServer.listen(3000, function(){
});

app.get('/hosts/reload/', function (req, res) {
  createWebSockets();
  res.send({success: "reloaded"});
})

createWebSockets();
