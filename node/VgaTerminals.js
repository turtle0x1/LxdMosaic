var WebSocket = require('ws');

module.exports = class VgaTerminals {
  constructor(rp, hosts) {
    this.rp = rp;
    this.hosts = hosts;
    this.instanceSockets = {};
  }

  openTerminal(clientSocket, req){
      this.hosts.loadHosts().then(hosts=>{
        let hostDetails = hosts[req.params.hostId];

        if(!hostDetails.supportsVms){
            clientSocket.close()
            return false;
        }

        let hostId = hostDetails.hostId;
        let instance = req.params.instance;
        let userId = req.query.user_id;
        let project = req.params.project;

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
                 clientSockets: [],
                 lxdSockets: [],
                 controlSocket: null,
                 operationDetails: {}
             };
         }

          this.getSocket(userId, hostDetails, project, instance).then((lxdWebSocket)=>{
              this.instanceSockets[hostId][project][instance][userId].clientSockets.push(clientSocket);
              this.instanceSockets[hostId][project][instance][userId].lxdSockets.push(lxdWebSocket);

              clientSocket.on("close", ()=>{
                  // Client is disconnecting / re-connecting - this will restart
                  // the control socket
                  this.instanceSockets[hostId][project][instance][userId].controlSocket.close()
              });

              clientSocket.on("message", (data)=>{
                  lxdWebSocket.send(data);
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

                   // Once the control socket is closed (when a client disconects)
                   // we know the user is no longer using the terminal so we
                   // delete the key so someone else can connect
                   controlWs.on("close", ()=>{
                       delete this.instanceSockets[hostDetails.hostId][project][instance][userId];
                   })

                   this.instanceSockets[hostDetails.hostId][project][instance][userId].controlSocket = controlWs

                   resolve(lxdWs)
               })
           }
      });
  }

  getOpUrl(hostDetails, operation, secret){
      return `wss://${hostDetails.hostWithOutProtoOrPort}:${hostDetails.port}${operation}/websocket?secret=${secret}`;
  }

  openLxdTerminal(hostDetails, project, instances) {
      let execOptions = {
        method: 'POST',
        uri: `https://${hostDetails.hostWithOutProtoOrPort}:${hostDetails.port}/1.0/instances/${instances}/console?project=${project}`,
        cert: hostDetails.cert,
        key: hostDetails.key,
        rejectUnauthorized: false,
        json: true,
        body: {
            "width": 0,
            "height": 0,
            "type": "vga"
        }
      }
      return this.rp(execOptions);
    }
};
