var WebSocket = require('ws');
var internalUuidv1 = require('uuid/v1');

module.exports = class HostOperations {
  constructor(hosts) {
    this.hosts = hosts;
    this.operationSockets = {};
    this.clientSockets = {};
  }

  sendToOpsClients(hostId, project, type, msg)
  {
      //Silly but sometimes msg is a string :shrug:
      if(typeof msg == "string"){
          msg = JSON.parse(msg);
      }
      msg.mosaicType = type;
      msg = JSON.stringify(msg);
      Object.keys(this.clientSockets[hostId][project]).forEach((uuid)=>{
          let socket = this.clientSockets[hostId][project][uuid]
          if(socket.readyState == 1){
              socket.send(msg);
          }else if(socket.readyState == 3){
              // This is an extra gaurd incase some sockets are missed
              // for some reason
              this.clientSockets[hostId][project][uuid].close()
              delete this.clientSockets[hostId][project][uuid]
          }
      })
  }

  addClientSocket(socket, userId, host, project)
  {
      this.hosts.loadHosts().then(hosts=>{
        let hostDetails = hosts[host];

        this.getSocket(userId, hostDetails, project).then(()=>{

        });

        if(!this.clientSockets.hasOwnProperty(hostDetails.hostId)){
            this.clientSockets[hostDetails.hostId] = {};
        }

        if(!this.clientSockets[hostDetails.hostId].hasOwnProperty(project)){
            this.clientSockets[hostDetails.hostId][project] = {}
        }

        let socketId = internalUuidv1();
        this.clientSockets[hostDetails.hostId][project][socketId] = socket

        socket.on('close', () => {
            this.clientSockets[hostDetails.hostId][project][socketId].close()
            delete this.clientSockets[hostDetails.hostId][project][socketId]
        });
      });

  }

  closeSockets() {
    Object.keys(this.operationSockets).forEach((host)=>{
        Object.keys(this.operationSockets[host]).forEach((project)=>{
            this.operationSockets[host][project].close();
        });
    });

    Object.keys(this.clientSockets).forEach((host)=>{
        Object.keys(this.clientSockets[host]).forEach((project)=>{
            Object.keys(this.clientSockets[host][project]).forEach(uuid=>{
                this.clientSockets[host][project][uuid].close();
                delete this.clientSockets[host][project][uuid]
            });
        });
    });
  }


  getSocket(userId, hostDetails, project){
      return new Promise((resolve, reject) => {
          const wsoptions = {
            cert: hostDetails.cert,
            key: hostDetails.key,
            rejectUnauthorized: false,
          };

           if(this.operationSockets.hasOwnProperty(hostDetails.hostId) && this.operationSockets[hostDetails.hostId].hasOwnProperty(project)){
               resolve(true);
           }else{
               if(!this.operationSockets.hasOwnProperty(hostDetails.hostId)){
                    this.operationSockets[hostDetails.hostId] = {};
               }

               this.operationSockets[hostDetails.hostId][project] = new WebSocket(
                 'wss://' + hostDetails.hostWithOutProto + `/1.0/events?type=operation&project=${project}`,
                 wsoptions
               );

               this.operationSockets[hostDetails.hostId][project].on('message', (data) => {
                 var buf = Buffer.from(data);
                 let message = JSON.parse(data.toString());

                 let alias = hostDetails.alias;
                 if(alias == null || alias == ""){
                     alias = hostDetails.hostWithOutProtoOrPort;
                 }
                 message.hostAlias = alias;
                 message.hostId = hostDetails.hostId;
                 message.host = hostDetails.hostWithOutProtoOrPort;
                 message.project = project;

                 if(message.hasOwnProperty("location") && message.location !== "" && message.location !== "none" && message.location !== details.alias){
                     return;
                 }

                 this.sendToOpsClients(hostDetails.hostId, project, "operationUpdate", message)
               });

               resolve(true)
           }
      });
  }
};
