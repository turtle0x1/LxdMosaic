var WebSocket = require('ws');
var internalUuidv1 = require('uuid/v1');

module.exports = class Terminals {
  constructor(rp) {
    this.rp = rp;
    this.activeTerminals = {};
    this.internalUuidMap = {};
  }

  getInternalUuid(host, container, cols, rows) {
    let key = `${host}.${container}`;
    if (this.internalUuidMap.hasOwnProperty(key)) {
      return this.internalUuidMap[key];
    }
    let internalUuid = internalUuidv1();

    this.internalUuidMap[key] = {
        "uuid": internalUuid,
        "cols": cols,
        "rows": rows
    };
    return internalUuid;
  }

  sendToTerminal(internalUuid, msg) {
    if (this.activeTerminals[internalUuid] == undefined) {
      return;
    }

    this.activeTerminals[internalUuid][0].send(
      msg,
      {
        binary: true,
      },
      () => {}
    );
  }

  resize(internalUuid, cols, rows) {
    if (this.activeTerminals[internalUuid] == undefined) {
      return;
    }

    this.internalUuidMap[internalUuid].cols = cols
    this.internalUuidMap[internalUuid].rows = rows

    this.activeTerminals[internalUuid]["control"].send(
      JSON.stringify({
          command: "window-resize",
          height: parseInt(rows),
          width: parseInt(cols)
      }),
      {
        binary: true,
      },
      () => {}
    );
  }

  close(internalUuid, exitCommand = "exit\r\n") {
    if (this.activeTerminals[internalUuid] == undefined) {
      return;
    }

    this.activeTerminals[internalUuid][0].send(
      exitCommand,
      { binary: true },
      () => {
        this.activeTerminals[internalUuid][0].close();
        this.activeTerminals[internalUuid]["control"].close();
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
    socket,
    hosts,
    host,
    project,
    container,
    internalUuid = null,
    shell = null,
    callbacks = null
  ) {
    return new Promise((resolve, reject) => {
      if (this.activeTerminals[internalUuid] !== undefined) {
        this.activeTerminals[internalUuid][0].on('error', error =>
          console.log(error)
        );

        this.activeTerminals[internalUuid][0].on("message", (data) => {
          const buf = Buffer.from(data);
          data = buf.toString();
          socket.send(data);
        });

        this.sendToTerminal(internalUuid, '\n');
        resolve(true);
        return;
      }

      let hostDetails = hosts[host];

      let cols = 80;
      let rows = 24;

      if (this.internalUuidMap.hasOwnProperty(`${host}.${container}`)) {
        cols = this.internalUuidMap[`${host}.${container}`].cols
        rows = this.internalUuidMap[`${host}.${container}`].rows
      }

      this.openLxdOperation(hostDetails, project, container, shell, cols, rows)
        .then(openResult => {
          let url = `wss://${hostDetails.hostWithOutProtoOrPort}:${hostDetails.port}`;

          // If the server dies but there are active clients they will re-connect
          // with their process-id but it wont be in the internalUuidMap
          // so we need to re add it
          if (!this.internalUuidMap.hasOwnProperty(`${host}.${container}`)) {
            this.internalUuidMap[`${host}.${container}`] = {
                "uuid": internalUuid,
                cols: null,
                rows: null,
            };
          }

          const wsoptions = {
            cert: hostDetails.cert,
            key: hostDetails.key,
            rejectUnauthorized: false,
          };

          let lxdWs = new WebSocket(
            url +
              openResult.operation +
              '/websocket?secret=' +
              openResult.metadata.metadata.fds['0'],
              wsoptions
          );

          let controlSocket = new WebSocket(
            url +
              openResult.operation +
              '/websocket?secret=' +
              openResult.metadata.metadata.fds['control'],
              wsoptions
          );

          lxdWs.on('error', error => console.log(error));

          lxdWs.on('message', data => {
              const buf = Buffer.from(data);
              data = buf.toString();
              
              if(typeof callbacks.formatServerResponse === "function"){
                  data = callbacks.formatServerResponse(data)
              }

              if(socket.readyState == 1){
                socket.send(data);
              }

              if(typeof callbacks.afterSeverResponeSent === "function"){
                  callbacks.afterSeverResponeSent(data)
              }
          });

          this.activeTerminals[internalUuid] = {
             0: lxdWs,
             "control": controlSocket
          };

          resolve(true);
        })
        .catch((e) => {
            console.log(e);
          reject();
        });
    });
  }

  openLxdOperation(hostDetails, project, container, shell, cols, rows) {
    let execOptions = this.createExecOptions(hostDetails, project, container);

    execOptions.body = this.getExecBody(shell, cols, rows);

    return this.rp(execOptions);
  }

  getExecBody(toUseShell = null, cols, rows) {
    let shell = ['bash'];

    if (typeof toUseShell == 'string' && toUseShell !== '') {
      shell = [toUseShell];
    }

    return {
      command: shell,
      environment: {
        HOME: '/root',
        TERM: 'xterm',
        USER: 'root',
      },
      'wait-for-websocket': true,
      interactive: true,
      height: parseInt(rows),
      width: parseInt(cols)
    };
  }

  createExecOptions(hostDetails, project, container) {
    let url = hostDetails.supportsVms ? 'instances' : 'containers';
    return {
      method: 'POST',
      uri: `https://${hostDetails.hostWithOutProtoOrPort}:${hostDetails.port}/1.0/${url}/${container}/exec?project=${project}`,
      cert: hostDetails.cert,
      key: hostDetails.key,
      rejectUnauthorized: false,
      json: true,
    };
  }
};
