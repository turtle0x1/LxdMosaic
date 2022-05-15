var WebSocket = require('ws');
var internalUuidv1 = require('uuid/v1');

module.exports = class Terminals {
    constructor(hosts) {
        this.hosts = hosts;
        this._terminalDetails = {}
    }

    getNewTerminalId(userId, hostId, project, instance, shell) {
        let terminalId = internalUuidv1();
        this._terminalDetails[terminalId] = {
            hostId: hostId,
            project: project,
            instance: instance,
            userId: userId,
            clientSocket: null,
            controlSocket: null,
            opSocket: null
        }
        return terminalId;
    }

    checkStatus(userId, terminalId){
        let response = {exists: false, inUse: false}
        if(!this._terminalDetails.hasOwnProperty(terminalId)){
            return response
        }
        let term = this._terminalDetails[terminalId]
        if(term.userId !== userId){
            return response
        }
        response.exists = true
        response.inUse = this._terminalDetails[terminalId].clientSocket == null;
        return response
    }

    proxyTerminal(
        terminalId,
        allowUserInput = true,
        callbacks = {}
    ) {
        return new Promise((resolve, reject) => {
            console.log(this._terminalDetails);
            if (!this._terminalDetails.hasOwnProperty(terminalId)) {
                console.error("trying to access terminal terminalId that doesn't exist");
                return false;
            }
            if (this._terminalDetails[terminalId].opSocket !== null) {
                this._configureClientSocket(clientSocket, terminalId, allowUserInput)

                this._terminalDetails[terminalId].opSocket.on('error', error =>
                    console.log(error)
                );

                this._terminalDetails[terminalId].opSocket.on("message", (data) => {
                    const buf = Buffer.from(data);
                    data = buf.toString();
                    this._terminalDetails[terminalId].lastMessage = data
                    if (clientSocket.readyState == 1) {
                        clientSocket.send(data);
                    }
                });

                resolve(true);
                return;
            }

            let host = this.hosts.getHosts()[this._terminalDetails[terminalId].hostId];
            let project = this._terminalDetails[terminalId].project
            let instance = this._terminalDetails[terminalId].instance
            let shell = this._terminalDetails[terminalId].shell

            this._openLxdSockets(host, project, instance, shell)
                .then(lxdResponse => {
                    const wsoptions = {
                        cert: host.cert,
                        key: host.key,
                        rejectUnauthorized: false,
                    };

                    let proto = 'wss://';
                    let target = `${host.hostWithOutProtoOrPort}:${host.port}`

                    let path = lxdResponse.operation + '/websocket?secret=';
                    let termSocketPath = path + lxdResponse.metadata.metadata.fds['0'];
                    let controlSocketPath = path + lxdResponse.metadata.metadata.fds['control'];

                    if (host.socketPath !== null) {
                        proto = 'ws+unix://'
                        target = host.socketPath
                        // Unix sockets need ":" between file path and http path
                        termSocketPath = ":" + termSocketPath;
                        // Unix sockets need ":" between file path and http path
                        controlSocketPath = ":" + controlSocketPath;
                    }

                    let url = `${proto}${target}`;
                    let lxdWs = new WebSocket(`${url}${termSocketPath}`, wsoptions);
                    let controlSocket = new WebSocket(`${url}${controlSocketPath}`, wsoptions);

                    controlSocket.on("close", () => {
                        //NOTE If you try to connect to a "bash" shell on an alpine instance
                        //     it "slienty" fails only closing the control clientSocket so we need
                        //     to tidy up the remaining sockets
                        lxdWs.close()
                        clientSocket.close()
                    });

                    this._configureClientSocket(clientSocket, terminalId, allowUserInput)

                    lxdWs.on('error', error => console.log(error));

                    lxdWs.on('message', data => {
                        const buf = Buffer.from(data);
                        data = buf.toString();

                        if (typeof callbacks.formatServerResponse === "function") {
                            data = callbacks.formatServerResponse(data)
                        }

                        if (clientSocket.readyState == 1) {
                            clientSocket.send(data);
                        }

                        if (typeof callbacks.afterSeverResponeSent === "function") {
                            callbacks.afterSeverResponeSent(data)
                        }
                    });

                    this._terminalDetails[terminalId].controlSocket = controlSocket
                    this._terminalDetails[terminalId].opSocket = lxdWs

                    resolve(true);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

    sendToTerminal(terminalId, msg) {
        if (this._terminalDetails.hasOwnProperty(terminalId) === false) {
            return;
        }

        this._terminalDetails[terminalId].opSocket.send(
            msg, {
                binary: true,
            },
            () => {}
        );
    }

    close(terminalId, exitCommand = "exit\r\n", gracePeriod = false) {
        if (this._terminalDetails.hasOwnProperty(terminalId) === false) {
            return;
        }

        this._terminalDetails[terminalId].clientSocket.close();
        this._terminalDetails[terminalId].clientSocket = null;

        let _postExitCommandSend = ()=>{
            // NOTE This timeout is required to stop LXD panicing (bug reported)
            setTimeout(() => {
                delete this._terminalDetails[terminalId];
            }, 100)
        }

        if(gracePeriod){
            setTimeout(()=>{
                if(this._terminalDetails.hasOwnProperty(terminalId) && this._terminalDetails[terminalId].clientSocket == null){
                    this._closeLxdSockets(terminalId, exitCommand, _postExitCommandSend)
                }
            }, (1000 * 60) * 5) // Wait 5 minutes before closing sockets with LXD)
        }else{
            this._closeLxdSockets(terminalId, exitCommand, _postExitCommandSend)
        }
    }

    closeAll() {
        let keys = Object.keys(this._terminalDetails);

        for (let i = 0; i < keys.length; i++) {
            this.close(keys[i]);
        }

        this._terminalDetails = {};
    }

    _closeLxdSockets(terminalId, exitCommand, exitCommandSentCallback){
        this._terminalDetails[terminalId].opSocket.close();
        this._terminalDetails[terminalId].controlSocket.close();
        this._terminalDetails[terminalId].opSocket.send(exitCommand, {binary: true}, exitCommandSentCallback);
    }

    _openLxdSockets(host, project, instance, shell, depth = 0) {
        return new Promise((resolve, reject) => {
            if (depth >= 5) {
                return reject(new Error("Reached max terminal connect retries"))
            }

            let execOptions = this._createLxdReqOpts(host, project, instance);

            let data = JSON.stringify(this._createLxdReqBody(shell))

            const callback = res => {
                res.setEncoding('utf8');
                let chunks = [];
                res.on('data', function(data) {
                    chunks.push(data);
                }).on('end', function() {
                    resolve(JSON.parse(chunks.join('')))
                }).on('error', function(data) {
                    this._openLxdSockets(host, terminalId, depth + 1)
                });
            };

            if (host.socketPath == null) {
                const clientRequest = this.https.request(execOptions, callback);
                clientRequest.write(data)
                clientRequest.end();
            } else {
                const clientRequest = this.http.request(execOptions, callback);
                clientRequest.write(data)
                clientRequest.end();
            }
        })
    }

    _configureClientSocket(clientSocket, terminalId, allowInput = true) {
        this._terminalDetails[terminalId].clientSocket = clientSocket

        //NOTE When user inputs from browser
        clientSocket.on("message", (msg) => {
            let resizeCommand = msg.match(/resize-window\:cols=([0-9]+)&rows=([0-9]+)/);
            if (resizeCommand) {
                this._resizeOnLxd(terminalId, resizeCommand[1], resizeCommand[2])
            } else if (allowInput) {
                this.sendToTerminal(terminalId, msg);
            }
        });

        clientSocket.on('error', () => {
            console.log("socket error");
        });

        clientSocket.on('close', () => {
            this.close(terminalId, "exit\r\n", true);
        });
    }

    _resizeOnLxd(terminalId, cols, rows) {
        if (this._terminalDetails.hasOwnProperty(terminalId) === false) {
            return;
        }

        this._terminalDetails[terminalId].controlSocket.send(
            JSON.stringify({
                command: "window-resize",
                args: {
                    height: `${parseInt(rows)}`,
                    width: `${parseInt(cols)}`
                }
            }), {
                binary: true,
            },
            () => {}
        );
    }

    _createLxdReqBody(toUseShell = null) {
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
            interactive: true
        };
    }

    _createLxdReqOpts(host, project, instance) {
        let url = host.supportsVms ? 'instances' : 'containers';
        const options = {
            method: 'POST',
            path: `/1.0/${url}/${instance}/exec?project=${project}`,
            cert: host.cert,
            key: host.key,
            rejectUnauthorized: false,
            json: true
        };

        if (host.socketPath == null) {
            options.host = host.hostWithOutProtoOrPort
            options.port = host.port
        } else {
            options.socketPath = host.socketPath
        }

        return options;

    }
};
