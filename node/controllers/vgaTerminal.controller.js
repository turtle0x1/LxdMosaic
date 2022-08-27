module.exports = class VgaTerminalController {
    constructor(vgaTerminals) {
        this._vgaTerminals = vgaTerminals
    }

    openTerminal = async (socket, req) => {
        let userId = req.query.user_id;
        let hostId = req.query.hostId;
        let project = req.query.project;
        let instance = req.query.instance;

        await this._vgaTerminals.openTerminal(socket, userId, hostId, project, instance)
    }
}
