var WebSocket = require('ws');
var internalUuidv1 = require('uuid/v1');

module.exports = class HostEvents {

    _allowedProjects;
    _hostSockets = {}
    _clientSockets = {}

    constructor(hosts, allowedProjects) {
        this.hosts = hosts;
        this._hostSockets = {};
        this._allowedProjects = allowedProjects
        this.clientSockets = {};

        // On application start open a socket to all hosts on all projects
        hosts.loadHosts().then(xyz => {
            Object.keys(xyz).forEach((key, _) => {
                this._openHostSocket(xyz[key])
            });
        })
    }

    addClientSocket(userId, clientSocket) {
        let uuid = internalUuidv1();
        this._clientSockets[uuid] = {
            socket: clientSocket,
            listeningTo: {}
        }
        this._clientSockets[uuid].socket.on("message", (data) => {
            data = JSON.parse(data)
            let hostId = data.hostId
            let project = data.project
            this._allowedProjects.canAccessHostProject(userId, hostId, project).then(allowed => {
                if (allowed === true) {
                    this._clientSockets[uuid].listeningTo[hostId] = project
                }
            })
        })
        this._clientSockets[uuid].socket.on("close", (data) => {
            delete this._clientSockets[uuid]
        })
    }

    closeAllSockets() {
        Object.keys(this._clientSockets).forEach((key, _) => {
            this._clientSockets[key].socket.close()
        })
        Object.keys(this._hostSockets).forEach((key, _) => {
            this._hostSockets[key].close()
        })
    }

    _sendToClients(hostDetails, message) {
        Object.keys(this._clientSockets).forEach((key, _) => {
            if (this._clientSockets[key].listeningTo.hasOwnProperty(message.hostId)) {
                if (this._clientSockets[key].listeningTo[message.hostId] == message.project) {
                    this._clientSockets[key].socket.send(JSON.stringify(message))
                }
            }
        });
    }

    _processLxdEvent(data, hostDetails) {
        var buf = Buffer.from(data);
        let message = JSON.parse(data.toString());
        let alias = hostDetails.alias;
        if (alias == null || alias == "") {
            alias = hostDetails.hostWithOutProtoOrPort;
        }
        message.hostAlias = alias;
        message.hostId = hostDetails.hostId;
        message.host = hostDetails.hostWithOutProtoOrPort;
        message.mosaicType = "operationUpdate";

        // If the message if from another node in a cluster
        if (message.hasOwnProperty("location") && message.location !== "" && message.location !== "none" && message.location !== details.alias) {
            return;
        }

        this._sendToClients(hostDetails, message)
    }

    _openHostSocket(hostDetails) {
        return new Promise((resolve, reject) => {
            const wsoptions = {
                cert: hostDetails.cert,
                key: hostDetails.key,
                rejectUnauthorized: false,
            };

            if (this._hostSockets.hasOwnProperty(hostDetails.hostId)) {
                resolve(true);
            } else {
                if (!this._hostSockets.hasOwnProperty(hostDetails.hostId)) {
                    this._hostSockets[hostDetails.hostId] = {};
                }

                let proto = 'wss://';
                let path = `/1.0/events?type=operation&all-projects=true`
                let target = hostDetails.hostWithOutProto
                if (hostDetails.socketPath !== null) {
                    proto = 'ws+unix://'
                    target = hostDetails.socketPath
                    path = ":" + path; // Unix sockets need ":" between file path and http path
                }

                this._hostSockets[hostDetails.hostId] = new WebSocket(
                    proto + target + path,
                    wsoptions
                );

                this._hostSockets[hostDetails.hostId].on('message', (data) => this._processLxdEvent(data, hostDetails))

                resolve(true)
            }
        });
    }
};
