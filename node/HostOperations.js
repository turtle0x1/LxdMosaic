var WebSocket = require('ws');

module.exports = class HostOperations {
  constructor(fs, webSocket) {
    this.fs = fs;
    this.operationSockets = {};
  }

  setupWebsockets(hostDetails, clientOperationSocket) {
    return new Promise((resolve, reject) => {
      let keys = Object.keys(hostDetails);
      for (let i = 0; i < keys.length; i++) {
        let details = hostDetails[keys[i]];

        const wsoptions = {
          cert: details.cert,
          key: details.key,
          rejectUnauthorized: false,
        };
        // Only create a socket if we don't already have one for the host
        if (!this.operationSockets.hasOwnProperty(details.hostId)) {
          this.operationSockets[details.hostId] = new WebSocket(
            'wss://' + details.hostWithOutProto + '/1.0/events?type=operation',
            wsoptions
          );

          this.operationSockets[details.hostId].on('message', function(
            data,
            flags
          ) {
            var buf = Buffer.from(data);
            let message = JSON.parse(data.toString());
            message.hostAlias = details.alias;
            message.hostId = details.hostId;
            message.host = details.hostWithOutProtoOrPort;
            if(message.hasOwnProperty("location") && message.location !== "" && message.location !== "none" && message.location !== details.alias){
                return;
            }
            clientOperationSocket.emit('operationUpdate', message);
          });
        }
      }
      resolve();
    });
  }

  closeSockets() {
    let keys = Object.keys(this.operationSockets);
    for (let i = 0; i < keys.length; i++) {
      this.operationSockets[keys[i]].close();
    }
  }
};
