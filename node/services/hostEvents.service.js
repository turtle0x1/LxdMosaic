import WebSocket from 'ws';
import { v1 as uuidv1 } from 'uuid';

export default class  HostEvents {

    _allowedProjects;
    _hostSockets = {}
    _clientSockets = {}
    _allowedAdminLifecycleMessages = [];
    _allowedAnyoneLifecycleMessages = [];

    constructor(hosts, allowedProjects) {
        this.hosts = hosts;
        this._hostSockets = {};
        this._allowedProjects = allowedProjects
        this.clientSockets = {};
        // A select list of lifecycle events to let through to avoid leaking
        // info we shouldn't
        this._allowedAdminLifecycleMessages = ["project-created", "project-deleted", "project-renamed"]
        this._allowedAnyoneLifecycleMessages = ["image-created", "image-deleted", "instance-deleted", "instance-paused", "instance-resumed", "instance-shutdown", "instance-started", "instance-stopped", "network-created", "network-deleted", "network-renamed", "profile-created", "profile-deleted", "profile-renamed", "storage-pool-created", "storage-pool-deleted"]
    }

    addClientSocket(clientSocket, userId){
        let uuid = uuidv1();
        this._clientSockets[uuid] = {
            socket: clientSocket,
            listeningTo: {},
            listeningToAdmin: false
        }
        this._clientSockets[uuid].socket.on("message", async (data) => {
            data = JSON.parse(data)
            let hostId = data.hostId
            let project = data.project

            // Open a socket to the host if we haven't seen it before
            if(!this._hostSockets.hasOwnProperty(hostId)) {
                await this._openHostSocket(await this.hosts.getHost(hostId))
            }

            let canAccessProject = await this._allowedProjects.canAccessHostProject(userId, hostId, project)
            if(canAccessProject){
                this._clientSockets[uuid].listeningTo[hostId] = project
            }

            let isAdmin = await this._allowedProjects.isAdmin(userId)
            if(isAdmin) {
                this._clientSockets[uuid].listeningToAdmin = true
            }
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

    _sendToAdminClients(message) {
        Object.keys(this._clientSockets).forEach((key, _) => {
            if (this._clientSockets[key].listeningToAdmin === true) {
                this._clientSockets[key].socket.send(JSON.stringify(message))
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

        if (message.type == "lifecycle") {
            message.mosaicType = "lifecycle"
            if (this._allowedAdminLifecycleMessages.includes(message.metadata.action)) {
                this._sendToAdminClients(message)
                return false;
            }

            if (!this._allowedAnyoneLifecycleMessages.includes(message.metadata.action)) {
                return false;
            }
        }

        // If the message if from another node in a cluster
        if (message.hasOwnProperty("location") && message.location !== "" && message.location !== "none" && message.location !== hostDetails.alias) {
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
                let path = `/1.0/events?type=operation,lifecycle&all-projects=true`
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
