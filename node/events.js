// This originated from https://gist.github.com/CalebEverett/bed94582b437ffe88f650819d772b682
// and was modified to suite our needs
const fs = require('fs');
const WebSocket = require('ws');

var privateKey  = fs.readFileSync('/etc/ssl/private/ssl-cert-snakeoil.key', 'utf8');
var certificate = fs.readFileSync('/etc/ssl/certs/ssl-cert-snakeoil.pem', 'utf8');
var lxdClientCert = "";
var lxdClientKey = "";

var credentials = {key: privateKey, cert: certificate};
var express = require('express');
var app = express();

var https = require('https');

// your express configuration here
var httpsServer = https.createServer(credentials, app);
var io = require('socket.io')(httpsServer);

// Connecting to the lxd server/s
const wsoptions = {
    cert: fs.readFileSync(lxdClientCert),
    key: fs.readFileSync(lxdClientKey),
    rejectUnauthorized: false
}

var ws = new WebSocket('wss://192.168.1.6:8443/1.0/events?type=operation', wsoptions);

ws.on('open', function open() {
  console.log('open for notifications')
});

ws.on('error', (error) => {
  console.log(error)
});

ws.on('message', function(data, flags) {
  var buf = Buffer.from(data)
  let message = JSON.parse(data.toString());
  io.emit('operationUpdate', message);
});

//NOTE We run this socket there are no express endpoints (yet ?)

httpsServer.listen(3000, function(){
  console.log('listening on *:3000');
});
