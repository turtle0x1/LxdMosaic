import WebSocket from 'ws';
import { v1 as uuidv1 } from 'uuid';
import http from 'http';
import https from 'https';


export default class  Terminals {
  constructor(hosts) {
    this._hosts = hosts
    this.activeTerminals = {};
    this.internalUuidMap = {};
  }

  getInternalUuid(host, container, cols, rows) {
    let key = `${host}.${container}`;
    let knownInternalId = this.internalUuidMap.hasOwnProperty(key) ? this.internalUuidMap[key].uuid : false

    if (knownInternalId && this.activeTerminals.hasOwnProperty(knownInternalId) && this.activeTerminals[knownInternalId].closing !== true) {
      return this.internalUuidMap[key].uuid
    }
    let internalUuid = uuidv1();

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
    let key = Object.keys(this.internalUuidMap).filter((key) => {return this.internalUuidMap[key].uuid === internalUuid})[0];

    this.internalUuidMap[key].cols = cols
    this.internalUuidMap[key].rows = rows

    this.activeTerminals[internalUuid]["control"].send(
      JSON.stringify({
          command: "window-resize",
          args: {
              height: `${parseInt(rows)}`,
              width: `${parseInt(cols)}`
          }
      }),
      {
        binary: true,
      },
      () => {}
    );
  }

  close(internalUuid, exitCommand = "exit\r\n") {
      Object.keys(this.internalUuidMap).forEach(key =>{
          if(this.internalUuidMap[key].uuid == internalUuid){
              delete this.internalUuidMap[key];
              return false;
          }
      });
    if (this.activeTerminals[internalUuid] == undefined) {
      return;
    }

    this.activeTerminals[internalUuid].closing = true
    this.activeTerminals[internalUuid][0].send(
      exitCommand,
      { binary: true },
      () => {
          // NOTE This timeout is required to stop LXD panicing (bug reported)
          setTimeout(()=>{
              this.activeTerminals[internalUuid][0].close();
              this.activeTerminals[internalUuid]["control"].close();
              delete this.activeTerminals[internalUuid];
          }, 100)
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

  createTerminalIfReq = async(
    socket,
    host,
    project,
    container,
    internalUuid = null,
    shell = null,
    callbacks = {}) => {
    let hostDetails = await this._hosts.getHost(host)
    return new Promise((resolve, reject) => {
      if (this.activeTerminals[internalUuid] !== undefined) {
        this.activeTerminals[internalUuid][0].on('error', error =>
          console.log(error)
        );

        this.activeTerminals[internalUuid][0].on("message", (data) => {
          const buf = Buffer.from(data);
          data = buf.toString();
          if(socket.readyState == 1){
            socket.send(data);
          }

        });

        this.sendToTerminal(internalUuid, '\n');
        resolve(true);
        return;
      }

      let cols = this.internalUuidMap[`${host}.${container}`].cols;
      let rows = this.internalUuidMap[`${host}.${container}`].rows;

      if (this.internalUuidMap.hasOwnProperty(`${host}.${container}`)) {
        cols = this.internalUuidMap[`${host}.${container}`].cols
        rows = this.internalUuidMap[`${host}.${container}`].rows
      }

      this.openLxdOperation(hostDetails, project, container, shell, cols, rows)
        .then(openResult => {
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

        let proto = 'wss://';
        let target = `${hostDetails.hostWithOutProtoOrPort}:${hostDetails.port}`

        let path =  openResult.operation + '/websocket?secret=';
        let termSocketPath =  path + openResult.metadata.metadata.fds['0'];
        let controlSocketPath =  path + openResult.metadata.metadata.fds['control'];

        if(hostDetails.socketPath !== null){
            proto = 'ws+unix://'
            target = hostDetails.socketPath
            termSocketPath = ":" + termSocketPath; // Unix sockets need ":" between file path and http path
            controlSocketPath = ":" + controlSocketPath; // Unix sockets need ":" between file path and http path
        }

        let url = `${proto}${target}`;
        let lxdWs = new WebSocket(`${url}${termSocketPath}`, wsoptions);
        let controlSocket = new WebSocket(`${url}${controlSocketPath}`, wsoptions);

          controlSocket.on("close", ()=>{
            //NOTE If you try to connect to a "bash" shell on an alpine instance
            //     it "slienty" fails only closing the control socket so we need
            //     to tidy up the remaining sockets
            lxdWs.close()
            socket.close()
          });

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
            reject(e);
        });
    });
  }

  openLxdOperation(hostDetails, project, container, shell, cols, rows, depth = 0) {
      return new Promise((resolve, reject) => {
          if(depth >= 5){
              return reject(new Error("Reached max terminal connect retries"))
          }
          let execOptions = this.createExecOptions(hostDetails, project, container);

          let data = JSON.stringify(this.getExecBody(shell, cols, rows))

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

          if(hostDetails.socketPath == null){
              const clientRequest = https.request(execOptions, callback);
              clientRequest.write(data)
              clientRequest.end();
          }else{
              const clientRequest = http.request(execOptions, callback);
              clientRequest.write(data)
              clientRequest.end();
          }
      })
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

    const options = {
        method: 'POST',
        path: `/1.0/${url}/${container}/exec?project=${project}`,
        cert: hostDetails.cert,
        key: hostDetails.key,
        rejectUnauthorized: false,
        json: true
    };

    if(hostDetails.socketPath == null){
        options.host = hostDetails.hostWithOutProtoOrPort
        options.port = hostDetails.port
    }else{
        options.socketPath = hostDetails.socketPath
    }

    return options;

  }
};
