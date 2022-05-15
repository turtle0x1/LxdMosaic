const currentFloatingTerminalObjs = {};

function setupFloatingTerminal(){
    $("#floatingWindowConsole").resizable({
        handles: "n",
        containment: "#mainContainerDiv",
        maxHeight: $("#mainContainerDiv").height() - 10,
        minHeight: $("#floatingConsoleTabsDiv").height() + 5
    })

    $("#floatingWindowConsole").show();
    $("#sidebarFooter").find("#openFloatingTerminal").removeClass("btn-outline-secondary").addClass("btn-secondary")


    $("#floatingConsoleActionTabs").on("click", "#openTabFloatingTerminal", function(){
        // TODO Plus button
    })

    $("#floatingConsoleActionTabs").on("click", "#expandFloatingTerminal", function(){
        let currentHeight = $("#floatingWindowConsole").height()
        let fullHeight = $("#mainContainerDiv").height() - 10
        if(currentHeight == fullHeight){
            $("#floatingWindowConsole").height($("#floatingConsoleActionTabs").height())
        }else{
            $("#floatingWindowConsole").height(fullHeight)
        }

        $("#floatingWindowConsole").trigger("resize")
    })

    $("#floatingConsoleActionTabs").on("click", "#hideFloatingTerminal", function(){
        // TODO Hide button
        $("#floatingWindowConsole").hide();
        $("#sidebarFooter").find("#openFloatingTerminal").removeClass("btn-secondary").addClass("btn-outline-secondary")
        $(document).focus()
    })

    let sessions = JSON.parse(localStorage.getItem("floatingTerminalSessions"));
    let firstKey = Object.keys(sessions)[0]
    let toRemove = [];
    let width = 0;
    let maxWidth = $("#floatingConsoleInstanceTabs").width();
    let currentWidth = 0;
    $.each(sessions, (terminalId, terminal)=>{
        checkTerminalStatus(terminalId, (data)=>{
            if(data.exists == false){
                toRemove.push(terminalId)
                // openFloatingTerminal(terminal.hostId, terminal.project, terminal.instance, terminal.shell)
            }else{
                let active = terminalId == firstKey ? "active" : ""
                let x = $(`<li class="nav-item d-inline viewFloatingTerminal" data-terminal-id="${terminalId}">
                  <div style="line-height: 2" class="nav-link ${active} d-flex" aria-current="page">
                      <div class="d-inline my-auto me-3">
                          <i class="fas fa-terminal text-primary"></i>
                      </div>
                      <div class="d-inline">
                      <div>
                        <i class="fas fa-box me-2"></i>${terminal.instance}
                      </div>
                      <div class="me-2 d-inline"><i class="fas fa-server me-2"></i>${hostsAliasesLookupTable[terminal.hostId]}</div>
                      <div class="d-inline"><i class="fas fa-project-diagram me-2"></i>${terminal.project}</div>
                      </div>
                  </div>
                </li>`)
                $("#floatingConsoleInstanceTabs").append(x)
                currentWidth += x.width()
                if(currentWidth > maxWidth){
                    if($("#floatingTerminalInstanceMoreBtn").length == 0){
                        $("#floatingConsoleInstanceTabs").append(`<li class="nav-item dropdown text-center" id="floatingTerminalInstanceMoreBtn" style="width: ${maxWidth - (currentWidth - x.width())}">
                            <div id="openTabFloatingTerminal" style="line-height: 2" class="nav-link  dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                                <i style="min-width: 24px; line-height: 4;" class="fas fa-plus"></i>
                            </div>
                            <ul class="dropdown-menu w-100 bg-dark">
                                <li><a class="dropdown-item" href="#">New terminal</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <div id="moreFloatingConsoleEntries">
                                </div>
                            </ul>
                        </li>`)
                    }else{
                        $("#floatingTerminalInstanceMoreBtn").find("#moreFloatingConsoleEntries").append(`<li><a class="dropdown-item">${terminal.instance}</a></li>`)
                    }

                    x.remove()
                }

                // _openFloatingTerm(firstKey, terminalId == firstKey)
            }


        });
    })
    console.log(toRemove);

}


$(document).on("click", ".viewFloatingTerminal", function(){
    $("#floatingConsoleInstanceTabs").find(".active").removeClass("active")
    $(this).find(".nav-link").addClass("active");
    // TODO On change session
    $(".terminalScreen").hide()
    $("#floating-" + $(this).data("terminalId")).show();
    $("#floating-" + $(this).data("terminalId")).height($("#termia"));

})

function openFloatingTerminal(hostId, project, instance, shell){
    $.ajax({
        type: "POST",
        dataType: 'json',
        contentType: 'application/json',
        url: `/terminals?${$.param({
            ws_token: userDetails.apiToken,
            user_id: userDetails.userId
        })}`,
        data: JSON.stringify({
            hostId: hostId,
            instance: instance,
            project: project,
            shell: shell
        }),
        success: function(data) {
            let sessions = JSON.parse(localStorage.getItem("floatingTerminalSessions"))

            if(sessions == null){
                sessions = {};
            }
            sessions[data.terminalId] = {
                hostId: hostId,
                project: project,
                instance: instance,
                shell: shell
            }

            localStorage.setItem("floatingTerminalSessions", JSON.stringify(sessions))

            $("#floatingConsoleInstanceTabs").find(".active").removeClass("active");
            $("#floatingConsoleInstanceTabs").append(`<li class="nav-item d-inline viewFloatingTerminal" data-terminal-id="${data.terminalId}">
              <div style="line-height: 2" class="nav-link active d-flex" aria-current="page">
                  <div class="d-inline my-auto me-3">
                      <i class="fas fa-terminal text-primary"></i>
                  </div>
                  <div class="d-inline">
                  <div><i class="fas fa-box me-2"></i>${instance}</div>
                  <div class="me-2 d-inline"><i class="fas fa-server me-2"></i>${hostsAliasesLookupTable[hostId]}</div>
                  <div class="d-inline"><i class="fas fa-project-diagram me-2"></i>${project}</div>
                  </div>
              </div>
            </li>`)
            //TODO VMS
            // <li class="nav-item">
            //   <a style="line-height: 2" class="nav-link d-flex" aria-current="page" href="#">
            //       <div class="d-inline my-auto me-3">
            //           <i class="fas fa-desktop text-primary"></i>
            //       </div>
            //       <div class="d-inline">
            //       <div><i class="fas fa-vr-cardboard me-2"></i>Active</div>
            //       <div class="me-2 d-inline"><i class="fas fa-server me-2"></i>Alias</div>
            //       <div class="d-inline"><i class="fas fa-project-diagram me-2"></i>default</div>
            //       </div>
            //   </a>
            // </li>
            _openFloatingTerm(data.terminalId)
        },
        error: function(){
            // localTerm.writeln("LXDMosaic: Node server cant be reached.")
            // localTerm.writeln("Please report this to your admin.")
            // $("#terminalControls").find(".btn-toolbar").fadeOut(2000)
            // return false;
        },
        dataType: "json"
    });
    return false;
}

function _openFloatingTerm(terminalId, show = true){
    let terminalHtmlId = "floating-" + terminalId
    $(".terminalScreen").hide()

    let localfitAddon = new window.FitAddon.FitAddon()
    let localTerm = new Terminal({});

    if($("#" + terminalHtmlId).length === 0){
        $("#floatConsoleTermsDiv").append(`<div id="${terminalHtmlId}" class="terminalScreen"></div>`)
        if(show){
            $("#" + terminalHtmlId).show()
        }
    }

    // $("#floatConsoleTermsDiv").height($("#floatConsoleTermsDiv").height())
    const terminalContainer = $("#floatConsoleTermsDiv").find("#" + terminalHtmlId )[0]

    // Clean terminal
    while (terminalContainer.children.length) {
        terminalContainer.removeChild(terminalContainer.children[0]);
    }

    localTerm.loadAddon(localfitAddon)
    localTerm.open(terminalContainer);
    localfitAddon.fit()

    if(show){
        $("#floatConsoleTermsDiv").find("#" + terminalHtmlId ).show()
    }

    // Theoretically no need to inject credentials
    // here as auth is only called when a socket
    // is first connected (in this case when the
    // operations socket is setup - which will
    // always come before this) but to be safe ...
    let localConsoleSocket = new WebSocket(`wss://${getQueryVar("host", window.location.hostname)}:${getQueryVar("port", 443)}/node/console?${$.param({
        ws_token: userDetails.apiToken,
        user_id: userDetails.userId,
        terminalId: terminalId
    })}`);

    localConsoleSocket.onclose = function(){
        localTerm.writeln("")
        localTerm.writeln("LXDMosaic: Shell closed, if this is un-expected it could be:")
        localTerm.writeln("")
        localTerm.writeln("  - A network error")
        localTerm.writeln("  - The instance was turned off")
        localTerm.writeln("  - LXDMosaic is missbehaving")
        localTerm.writeln("  - You're trying to use a shell not installed (I.E bash instead of ash)")
    };

    $("#" + terminalId).height($("#floatConsoleTermsDiv").height())
    localTerm.loadAddon(new window.AttachAddon.AttachAddon(localConsoleSocket));
    $("#terminalControls").find(".btn-toolbar").fadeOut(2000)

    currentFloatingTerminalObjs[terminalId] = {
        clientSocket: localConsoleSocket,
        termObj: localTerm,
        fitObj: localfitAddon
    }

    setTimeout(() => {
        let x = localfitAddon.proposeDimensions();
        //NOTE send two requests, this seems to force the server to respond
        //     with something, preventing the user seeing a blank screen
        //     if something producing output isn't running
        localConsoleSocket.send(`resize-window:cols=${x.cols - 1}&rows=${x.rows - 1}`)
        localConsoleSocket.send(`resize-window:cols=${x.cols}&rows=${x.rows}`)
    }, 500) // 500 magic number, waiting for socket ready wasn't long enough :shrug:
}

$(document).on("resize", '#floatingWindowConsole', function(){
    let newHeight = $("#floatingWindowConsole").height() - $("#floatingConsoleTabsDiv").height()
    $("#floatingConsoleMainDisplay").height(newHeight)
    $("#floatConsoleTermsDiv").height(newHeight)
    let visible = $("#floatConsoleTermsDiv").find(".terminalScreen:visible")

    $("#floatConsoleTermsDiv").find(".terminalScreen").each(function(){
        let terminalId = $(this).attr("id").replace("floating-", "")
        $(this).height(newHeight)
        let newDimensions = currentFloatingTerminalObjs[visible.attr("id").replace("floating-", "")].fitObj.proposeDimensions()

        currentFloatingTerminalObjs[terminalId].clientSocket.send(`resize-window:cols=${newDimensions.cols}&rows=${newDimensions.rows}`)
        // localfitAddon.fit() just didn't work
        currentFloatingTerminalObjs[terminalId].termObj._core._renderService.clear();
        currentFloatingTerminalObjs[terminalId].termObj.resize(newDimensions.cols, newDimensions.rows)
    });
});

function checkTerminalStatus(terminalId, callback){
    $.ajax({
        type: "POST",
        dataType: 'json',
        contentType: 'application/json',
        url: `/terminals/checkStatus?${$.param({
            ws_token: userDetails.apiToken,
            user_id: userDetails.userId}
        )}`,
        headers: $.extend({user_id: userDetails.userId}, userDetails),
        data: JSON.stringify({
            terminalId: terminalId
        }),
        success: callback,
        error: callback,
        dataType: "json"
    });
}
