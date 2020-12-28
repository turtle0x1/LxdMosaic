var WebSocket = require('ws');
var internalUuidv1 = require('uuid/v1');

module.exports = class Terminals {
  constructor(rp) {
    this.rp = rp;
    this.activeTerminals = {};
    this.internalUuidMap = {};
  }

  getInternalUuid(host, container) {
    let key = `${host}.${container}`;
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
        binary: true,
      },
      () => {}
    );
  }

  close(internalUuid) {
    if (this.activeTerminals[internalUuid] == undefined) {
      return;
    }

    this.activeTerminals[internalUuid].send(
      'exit\r\n',
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
    socket,
    hosts,
    host,
    project,
    container,
    internalUuid = null,
    shell = null
  ) {
    return new Promise((resolve, reject) => {
      if (this.activeTerminals[internalUuid] !== undefined) {
        this.activeTerminals[internalUuid].on('error', error =>
          console.log(error)
        );

        this.activeTerminals[internalUuid].on("message", (data) => {
          const buf = Buffer.from(data);
          data = buf.toString();
          socket.send(data);
        });

        this.sendToTerminal(internalUuid, '\n');
        resolve(true);
        return;
      }

      let hostDetails = hosts[host];

      this.openLxdOperation(hostDetails, project, container, shell)
        .then(openResult => {
          let url = `wss://${hostDetails.hostWithOutProtoOrPort}:${hostDetails.port}`;

          // If the server dies but there are active clients they will re-connect
          // with their process-id but it wont be in the internalUuidMap
          // so we need to re add it
          if (!this.internalUuidMap.hasOwnProperty(`${host}.${container}`)) {
            this.internalUuidMap[`${host}.${container}`] = internalUuid;
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

          lxdWs.on('error', error => console.log(error));

          lxdWs.on('message', data => {
              const buf = Buffer.from(data);
              data = buf.toString();
              if(socket.readyState == 1){
                socket.send(data);
              }
          });

          this.activeTerminals[internalUuid] = lxdWs;

          resolve(true);
        })
        .catch(() => {
          reject();
        });
    });
  }

  openLxdOperation(hostDetails, project, container, shell) {
    let execOptions = this.createExecOptions(hostDetails, project, container);

    execOptions.body = this.getExecBody(shell);

    return this.rp(execOptions);
  }

  getExecBody(toUseShell = null) {
    let shell = ['bash'];

    if (typeof shell == 'string' && shell !== '') {
      shell = [shell];
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
