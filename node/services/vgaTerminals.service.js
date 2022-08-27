var WebSocket = require('ws');
var internalUuidv1 = require('uuid/v1');
var http = require('http');
var https = require('https');

module.exports = class VgaTerminals {


  constructor(hosts) {
    this.hosts = hosts;
    this.instanceSockets = {};
  }

  openTerminal = (clientSocket, userId, hostId, project, instance)=>{
      this.hosts.loadHosts().then(hosts=>{
        let hostDetails = hosts[hostId];

        if(!hostDetails.supportsVms){
            clientSocket.close()
            return false;
        }


        if(!this.instanceSockets.hasOwnProperty(hostId)){
            this.instanceSockets[hostId] = {}
        }

        if(!this.instanceSockets[hostId].hasOwnProperty(project)){
            this.instanceSockets[hostId][project] = {}
        }

        if(!this.instanceSockets[hostId][project].hasOwnProperty(instance)){
            this.instanceSockets[hostId][project][instance] = {}
        }

        // Only allow one user on an instances terminal at a time
        if(Object.keys(this.instanceSockets[hostId][project][instance]).length == 1){
            if(!this.instanceSockets[hostId][project][instance].hasOwnProperty(userId)){
                clientSocket.close();
                return false;
            }
         }

         if(!this.instanceSockets[hostId][project][instance].hasOwnProperty(userId)){
             this.instanceSockets[hostId][project][instance][userId] = {
                 clientSockets: {},
                 lxdSockets: {},
                 controlSocket: null,
                 operationDetails: {}
             };
         }

          this.getSocket(userId, hostDetails, project, instance).then((lxdWebSocket)=>{
              let lxdSocketId = internalUuidv1();
              let clientSocketId = internalUuidv1();
              this.instanceSockets[hostId][project][instance][userId].clientSockets[clientSocketId] = clientSocket
              this.instanceSockets[hostId][project][instance][userId].lxdSockets[lxdSocketId] = lxdWebSocket

              clientSocket.on("close", ()=>{
                  // Close and delete the socket with LXD
                  if(this.instanceSockets[hostId][project][instance][userId].lxdSockets[lxdSocketId] != undefined){
                      this.instanceSockets[hostId][project][instance][userId].lxdSockets[lxdSocketId].close()
                      delete this.instanceSockets[hostId][project][instance][userId].lxdSockets[lxdSocketId];
                  }
                  // Delete our copy of the client socket and close the lxd
                  // control socket if the user doesnt re-connect within 2
                  // seconds
                  if(this.instanceSockets[hostId][project][instance][userId].clientSockets[clientSocketId] != undefined){
                      delete this.instanceSockets[hostId][project][instance][userId].clientSockets[clientSocketId];

                      if(Object.keys(this.instanceSockets[hostId][project][instance][userId].clientSockets).length == 0){
                          setTimeout(()=>{
                              if(Object.keys(this.instanceSockets[hostId][project][instance][userId].clientSockets).length == 0){
                                  if(this.instanceSockets[hostId][project][instance][userId].controlSocket != null){
                                      this.instanceSockets[hostId][project][instance][userId].controlSocket.close()
                                  }
                              }
                          }, 2000)
                      }
                  }
              });

              clientSocket.on("message", (data)=>{
                  if(lxdWebSocket.readyState == 1) {
                      lxdWebSocket.send(data);
                  }
              });

              lxdWebSocket.on('message', (data) => {
                  if(clientSocket.readyState == 1) {
                      clientSocket.send(data);
                   }

              });
          }).catch((err)=>{
              console.log(err);
          });
      });
  }

  getSocket(userId, hostDetails, project, instance){
      return new Promise((resolve, reject) => {

          const wsoptions = {
            cert: hostDetails.cert,
            key: hostDetails.key,
            rejectUnauthorized: false,
          };

           // If the user already has an operation going we only need to open
           // another websocket to LXD for each "channel" (mouse, keyboard ETC)
           // html5spice wants to use - we dont need another LXD control socket
           if(this.instanceSockets[hostDetails.hostId][project][instance].hasOwnProperty(userId) && this.instanceSockets[hostDetails.hostId][project][instance][userId].controlSocket != null){

               let operation = this.instanceSockets[hostDetails.hostId][project][instance][userId].operationDetails
               let url = this.getOpUrl(hostDetails, operation.operation, operation.metadata.metadata.fds['0']);
               var lxdWs = new WebSocket(url, wsoptions);
               resolve(lxdWs)
           // This runs on the first connection from the client, this opens
           // the operation & first websocket
           }else{
               this.openLxdTerminal(hostDetails, project, instance).then((operation)=>{
                   this.instanceSockets[hostDetails.hostId][project][instance][userId].operationDetails = operation;

                   let url = this.getOpUrl(hostDetails, operation.operation, operation.metadata.metadata.fds['0']);
                   let controlUrl = this.getOpUrl(hostDetails, operation.operation, operation.metadata.metadata.fds['control']);

                   var lxdWs = new WebSocket(url, wsoptions);
                   var controlWs = new WebSocket(controlUrl, wsoptions);

                   controlWs.on("close", ()=>{
                       this.instanceSockets[hostDetails.hostId][project][instance][userId].controlSocket = null;
                       if(Object.keys(this.instanceSockets[hostDetails.hostId][project][instance][userId].clientSockets).length == 0 && Object.keys(this.instanceSockets[hostDetails.hostId][project][instance][userId].lxdSockets).length == 0){
                           delete this.instanceSockets[hostDetails.hostId][project][instance][userId]
                       }
                   })

                   this.instanceSockets[hostDetails.hostId][project][instance][userId].controlSocket = controlWs

                   resolve(lxdWs)
               })
           }
      });
  }

  getOpUrl(hostDetails, operation, secret){
      let proto = 'wss://';
      let path = `${operation}/websocket?secret=${secret}`
      let target = `${hostDetails.hostWithOutProtoOrPort}:${hostDetails.port}`
      if(hostDetails.socketPath !== null){
          proto = 'ws+unix://'
          target = hostDetails.socketPath
          path = ":" + path; // Unix sockets need ":" between file path and http path
      }
      return `${proto}${target}${path}`;
  }

  openLxdTerminal(hostDetails, project, instances) {
      return new Promise((resolve, reject)=>{
          let execOptions = {
            method: 'POST',
            path: `/1.0/instances/${instances}/console?project=${project}`,
            cert: hostDetails.cert,
            key: hostDetails.key,
            rejectUnauthorized: false,
            json: true
          }
          const callback = res => {
            res.setEncoding('utf8');
            let chunks = [];
            res.on('data', function(data) {
              chunks.push(data);
            }).on('end', function() {
              resolve(JSON.parse(chunks.join('')))
            }).on('error', function(data){
                this.openLxdOperation(hostDetails, project, container, shell, cols, rows, depth + 1)
            });
          };

          let data  = JSON.stringify({
              "width": 0,
              "height": 0,
              "type": "vga"
          })

          if(hostDetails.socketPath == null){
              execOptions.host = hostDetails.hostWithOutProtoOrPort
              execOptions.port = hostDetails.port
              const clientRequest = https.request(execOptions, callback);
              clientRequest.write(data)
              clientRequest.end();
          }else{
              execOptions.socketPath = hostDetails.socketPath
              const clientRequest = http.request(execOptions, callback);
              clientRequest.write(data)
              clientRequest.end();
          }
      })
    }
};
