var WebSocket = require('ws');

module.exports = class HostOperations {
  constructor(hosts) {
    this.hosts = hosts;
    this.operationSockets = {};
    this.clientSockets = [];
  }

  sendToOpsClients(hostId, project, type, msg)
  {
      //Silly but sometimes msg is a string :shrug:
      if(typeof msg == "string"){
          msg = JSON.parse(msg);
      }
      msg.mosaicType = type;
      msg = JSON.stringify(msg);
      this.clientSockets[hostId][project].forEach(socket=>{
          if(socket.readyState == 1){
              socket.send(msg);
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
            this.clientSockets[hostDetails.hostId][project] = [];
        }

        this.clientSockets[hostDetails.hostId][project].push(socket)
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
            this.clientSockets[host][project].forEach((_, i)=>{
                this.clientSockets[host][project][i].close();
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
