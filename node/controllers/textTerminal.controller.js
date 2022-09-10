module.exports = class TextTerminalController {
    constructor(terminals) {
        this._terminals = terminals
    }

    getNewTerminalProcess = async (req, res) => {
        // Create a identifier for the console, this should allow multiple consolses
        // per user
        let terminalId = this._terminals.getNewTerminalId(req.body.user_id, req.body.hostId, req.body.project, req.body.instance, req.body.shell, req.query.cols, req.query.rows);
        res.json({ terminalId: terminalId });
        res.send();
    }

    openTerminal = async (socket, req) => {
        this._terminals.proxyTerminal(
            req.query.terminalId,
            socket
        ).catch(function(e){
            console.log(e);
        })
    }
}
