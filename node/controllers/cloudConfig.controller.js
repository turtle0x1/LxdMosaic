module.exports = class CloudConfigController {
    _startBlueText = '\x1B[34m';
    _endBlueText = '\x1B[0m';
    _dangerRegexs = [
        // A command not found in cloud-config `runcmd` array
        new RegExp('\/var\/lib\/cloud\/instance\/scripts\/runcmd:.* not found'),
        // Failed to install pacakges found in cloud-config `packages` array
        new RegExp('.*util.py\\[WARNING\\]: Failed to install packages:'),
        // Failed to run a generic cloud-config module
        new RegExp('.*cc_scripts_user.py\\[WARNING\\]: Failed to run module'),
        // Failed to run `packages` module
        new RegExp(".*cc_package_update_upgrade_install.py\\[WARNING\\]"),
        // Failed to read cloud-config data properly
        new RegExp(".*__init__.py\\[WARNING\\]: Unhandled non-multipart \\(text\\/x-not-multipart\\) userdata:.*")
    ];
    _messageCallbacks = {
        formatServerResponse: function(data) {
            let x = data.split("\r\n")

            if (x[0].match("exit") || x[0] == "^C") {
                return ''
            } else if (data.match(".*until \\[ \\-f")) {
                return this._startBlueText + data + this._endBlueText
            }

            let finishedLine = false;
            x.forEach((line, i) => {
                let isStartLine = line.match(".*until \\[ \\-f") !== null
                let isEndLine = line.match(/Cloud\-init.*finished/g) !== null
                if (isStartLine || isEndLine) {
                    if (isEndLine) {
                        finishedLine = true;
                    }
                    // Make text blue
                    x[i] = this._startBlueText + line + this._endBlueText
                } else {
                    this._dangerRegexs.forEach(regex => {
                        if (line.match(regex)) {
                            x[i] = this._startBlueText + line + this._endBlueText
                        }
                    });
                }
            });
            // If its the last line we want to remove the final "\n"
            if (finishedLine) {
                x.splice((x.length - 1), 1);
            }
            return x.join("\r\n")
        },
        afterSeverResponeSent: function(data) {
            // Check for the cloud-init finished message
            if (data.match(/Cloud\-init.*finished/g) !== null) {
                // `ctrl + c` out the `tail -f` command and exit
                this._terminals.sendToTerminal(uuid, `\x03`)
                this._terminals.close(uuid);
                socket.close()
            }
        }
    };

    constructor(terminals) {
        this._terminals = terminals
    }

    openTerminal = async (socket, req) => {
        let host = req.query.hostId,
            container = req.query.instance,
            uuid = this._terminals.getInternalUuid(req.body.host, req.body.container, req.query.cols ?? 80, req.query.rows ?? 24),
            shell = req.query.shell,
            project = req.query.project;

        await this._terminals.createTerminalIfReq(socket, host, project, container, uuid, shell, this._messageCallbacks)

        // Need to give the socket some time to establish before reading log file
        setTimeout(() => {
            this._terminals.sendToTerminal(uuid, 'until [ -f /var/log/cloud-init-output.log ];\ do sleep 1;\ done && tail -f /var/log/cloud-init-output.log\r\n')
        }, 100)

        //NOTE Ignore all user to input anything to this console

        socket.on('close', () => {
            this._terminals.close(uuid);
        });
    }
}
