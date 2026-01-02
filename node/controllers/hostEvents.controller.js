export default class  HostEventsController {
    constructor(hostEvents) {
        this._hostEvents = hostEvents
    }

    addClientSocket = async (clientSocket, req) => {
        let userId = req.query.user_id;
        this._hostEvents.addClientSocket(clientSocket, userId)
    }
}
