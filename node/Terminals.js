var WebSocket = require("ws");
var internalUuidv1 = require("uuid/v1");

module.exports = class Terminals {
  constructor(rp, mysqlCon) {
    this.rp = rp;
    this.mysql = mysqlCon;
    this.activeTerminals = {};
    this.internalUuidMap = {};
  }

  getInternalUuid(userId, host, container) {
    let key = `${userId}.${host}.${container}`;
    if (this.internalUuidMap.hasOwnProperty(key)) {
      return this.internalUuidMap[key];
    }
    let internalUuid = internalUuidv1();
    this.internalUuidMap[key] = internalUuid;
    return internalUuid;
  }

  sendToTerminal(internalUuid, msg) {
    if (this.activeTerminals[internalUuid] == undefined) {
      return;
    }

    this.activeTerminals[internalUuid].send(
      msg,
      {
        binary: true
      },
      () => {}
    );
  }

  close(internalUuid) {
    if (this.activeTerminals[internalUuid] == undefined) {
      return;
    }

    this.activeTerminals[internalUuid].send(
      "exit\r\n",
      { binary: true },
      () => {
        this.activeTerminals[internalUuid].close();
        delete this.activeTerminals[internalUuid];
      }
    );
  }

  closeAll() {
    let keys = Object.keys(this.activeTerminals);

    for (let i = 0; i < keys.length; i++) {
      this.close(keys[i]);
    }

    this.activeTerminals = {};
  }

  createTerminalIfReq(
    userId,
    socket,
    hosts,
    host,
    container,
    internalUuid = null,
    shell = null
  ) {
    return new Promise((resolve, reject) => {
      // If we already have an open socket for the provided uid
      if (this.activeTerminals[internalUuid] !== undefined) {
        this.activeTerminals[internalUuid].on("error", error =>
          console.log(error)
        );

        this.activeTerminals[internalUuid].on("message", data => {
          const buf = Buffer.from(data);
          data = buf.toString();
          socket.emit("data", data);
        });
        this.sendToTerminal(internalUuid, "\n");
        resolve(true);
        return;
      }

      let key = `${userId}.${host}.${container}`;
      let hostDetails = hosts[host];
      let url = `wss://${hostDetails.hostWithOutProtoOrPort}:${hostDetails.port}`;

      const wsoptions = {
        cert: hostDetails.cert,
        key: hostDetails.key,
        rejectUnauthorized: false
      };

      this.openOperationAlready(userId, host, container).then(data => {
        if (data.length > 0 && data[0].hasOwnProperty("TS_LXD_Secret")) {
            console.log("re-used man");
          this.createSocket(
            internalUuid,
            url,
            wsoptions,
            "/1.0/operations/" + data[0].TS_LXD_Operation_ID,
            data[0].TS_LXD_Secret,
            socket
          );
          resolve(true);
        } else {
          this.openLxdOperation(hostDetails, container, shell).then(
            openResult => {
              this.storeSocketDetails(
                userId,
                host,
                container,
                openResult.metadata.id,
                openResult.metadata.metadata.fds["0"]
              )
                .then(() => {
                  // If the server dies but there are active clients they will re-connect
                  // with their process-id but it wont be in the internalUuidMap
                  // so we need to re add it
                  if (!this.internalUuidMap.hasOwnProperty(key)) {
                    this.internalUuidMap[key] = internalUuid;
                  }

                  console.log("using me");
                  this.createSocket(
                    internalUuid,
                    url,
                    wsoptions,
                    openResult.operation,
                    openResult.metadata.metadata.fds["0"],
                    socket
                  );
                  resolve(true);
                })
                .catch(() => {
                    console.log("big wow");
                  reject();
                });
            }
          );
        }
      });
    });
  }

  createSocket(internalUuid, url, wsoptions, operationId, secret, socket) {

    const lxdWs = new WebSocket(
      url + operationId + "/websocket?secret=" + secret,
      wsoptions
    );

    lxdWs.on("error", error => {
        console.log("if server has un-expectedly closed socket");
        console.log(error)
    });

    lxdWs.on("message", data => {
      const buf = Buffer.from(data);
      data = buf.toString();
      socket.emit("data", data);
    });
    this.activeTerminals[internalUuid] = lxdWs;
  }

  openLxdOperation(hostDetails, container, shell) {
    let execOptions = this.createExecOptions(hostDetails, container);

    execOptions.body = this.getExecBody(shell);

    return this.rp(execOptions);
  }

  getExecBody(toUseShell = null) {
    let shell = ["bash"];

    if (typeof shell == "string" && shell !== "") {
      shell = [shell];
    }

    return {
      command: shell,
      environment: {
        HOME: "/root",
        TERM: "xterm",
        USER: "root"
      },
      "wait-for-websocket": true,
      interactive: true
    };
  }

  createExecOptions(hostDetails, container) {
    let url = hostDetails.supportsVms ? "instances" : "containers";
    return {
      method: "POST",
      uri: `https://${hostDetails.hostWithOutProtoOrPort}:${hostDetails.port}/1.0/${url}/${container}/exec`,
      cert: hostDetails.cert,
      key: hostDetails.key,
      rejectUnauthorized: false,
      json: true
    };
  }

  storeSocketDetails(userId, hostId, instance, operationId, operationSecret) {
    return new Promise((resolve, reject) => {
      this.mysql.query(
        "INSERT INTO `Terminal_Sessions` (`TS_User_ID`, `TS_Host_ID`, `TS_Instace`, `TS_LXD_Operation_ID`, `TS_LXD_Secret`) VALUES (?, ? , ? , ? ,?)",
        [userId, hostId, instance, operationId, operationSecret],
        function(err, results) {
          resolve(results);
        }
      );
    });
  }

  openOperationAlready(userId, hostId, instance) {
    return new Promise((resolve, reject) => {
      this.mysql.query(
        "SELECT `TS_LXD_Operation_ID`, `TS_LXD_Secret` FROM `Terminal_Sessions` WHERE `TS_User_ID` = ? AND `TS_Host_ID` = ? AND `TS_Instace` = ?",
        [userId, hostId, instance],
        function(err, results) {
          resolve(results);
        }
      );
    });
  }

  removeFromDb(operationId){
      return new Promise((resolve, reject) => {
        this.mysql.query(
          "DELETE FROM `Terminal_Sessions` WHERE `TS_LXD_Operation_ID` = ? ",
          [operationId],
          function(err, results) {
            resolve(results);
          }
        );
      });
  }
};
