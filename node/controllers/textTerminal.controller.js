module.exports = class TextTerminalController {
    constructor(terminals) {
        this._terminals = terminals
    }

    getNewTerminalProcess = async (req, res) => {
      // Create a identifier for the console, this should allow multiple consolses
      // per user
      let uuid = this._terminals.getInternalUuid(req.body.host, req.body.container, req.query.cols, req.query.rows);
      res.json({ processId: uuid });
      res.send();
    }

    openTerminal = async (socket, req) => {
        let host = req.query.hostId,
            container = req.query.instance,
            uuid = req.query.pid,
            shell = req.query.shell,
            project = req.query.project;

        await this._terminals.createTerminalIfReq(socket, host, project, container, uuid, shell)

        //NOTE When user inputs from browser
        socket.on("message", (msg) => {
            let resizeCommand = msg.match(/resize-window\:cols=([0-9]+)&rows=([0-9]+)/);
            if (resizeCommand) {
                this._terminals.resize(uuid, resizeCommand[1], resizeCommand[2])
            } else {
                this._terminals.sendToTerminal(uuid, msg);
            }
        });
        socket.on('error', () => {
            console.log("socket error");
        });
        socket.on('close', () => {
            this._terminals.close(uuid);
        });
    }
}
