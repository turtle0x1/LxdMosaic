var WebSocket = require("ws");

module.exports = class HostOperations {
  constructor(fs, terminals) {
      console.log(terminals);
    this.fs = fs;
    this.operationSockets = {};
    this.terminals = terminals;
  }

  setupWebsockets(hostDetails, clientOperationSocket) {
     let terminalFunctions = this.terminals;
    return new Promise((resolve, reject) => {
      let keys = Object.keys(hostDetails);
      for (let i = 0; i < keys.length; i++) {
        let details = hostDetails[keys[i]];

        const wsoptions = {
          cert: details.cert,
          key: details.key,
          rejectUnauthorized: false
        };
        // Only create a socket if we don't already have one for the host
        if (!this.operationSockets.hasOwnProperty(details.hostId)) {
          this.operationSockets[details.hostId] = new WebSocket(
            "wss://" + details.hostWithOutProto + "/1.0/events?type=operation",
            wsoptions
          );

          this.operationSockets[details.hostId].on("message", function(
            data,
            flags
          ) {

            var buf = Buffer.from(data);
            let message = JSON.parse(data.toString());
            message.host = details.hostWithOutProtoOrPort;

            if(message.metadata.description == "Executing command" && message.metadata.status_code == 200){
                terminalFunctions.removeFromDb(message.metadata.id);
            }

            clientOperationSocket.emit("operationUpdate", message);
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
