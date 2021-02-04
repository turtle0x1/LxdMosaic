var currentSockets = {};

function openHostOperationSocket(hostId, project)
{
    if(currentSockets.hasOwnProperty(hostId) && currentSockets[hostId].hasOwnProperty(project)){
        console.log("already got");
        return true;
    }else if(currentSockets.hasOwnProperty(hostId) && !currentSockets[hostId].hasOwnProperty(project) && Object.keys(currentSockets[hostId]).length > 0){
        Object.keys(currentSockets[hostId]).forEach((project)=>{
            currentSockets[hostId][project].close()
        });
    }

    let operationSocket = new WebSocket(`wss://${getQueryVar("host", window.location.hostname)}:${getQueryVar("port", 443)}/node/operations?ws_token=${userDetails.apiToken}&user_id=${userDetails.userId}&hostId=${hostId}&project=${project}`);

    operationSocket.onclose = (msg) => {
        delete currentSockets[hostId][project]
    }

    operationSocket.onmessage = (msg) => {
        msg = JSON.parse(msg.data);

        if(msg.mosaicType == "hostChange"){
            let data = $.parseJSON(msg);
            let status = data.offline ? "offline" : "online";
            makeServerChangePopup(status, data.host);
        }else if(msg.mosaicType == "deploymentProgress"){
            let tr = $("#deploymentContainersList").find("tr[data-deployment-container='" + msg.hostname + "']");
            if(tr.length > 0){
                $(tr).find("td:eq(5)").html(`<i class='fas fa-check'></i> ${moment().fromNow()}`);
            }
        }else if(msg.mosaicType == "operationUpdate"){
            let id = msg.metadata.id;
            let icon = statusCodeIconMap[msg.metadata.status_code];
            let description = msg.metadata.hasOwnProperty("description") ? msg.metadata.description : "No Description Available";
            let host = msg.host;
            let hostList = $("#operationsList").find(`[data-host='${host}']`);

            let loadServerOviewDescriptions = [
                "Transferring snapshot",
                "Creating container"
            ]

            if(loadServerOviewDescriptions.includes(msg.metadata.description)  && msg.metadata.status_code == 200 && $("#mainBreadcrumb").find(".active").text()){
                if($(`.nav-item[data-hostid='${msg.hostId}']`).hasClass("open")){
                    loadContainerTreeAfter(0, msg.hostId, msg.hostAlias);
                }
            }

            if(hostList.length == 0){
                $("#operationsList").append(`<div data-host='${host}' class="mt-2">
                <div class='text-center'>
                <h5><u>
                ${msg.hostAlias}
                </u></h5>
                </div>
                <div class='opList'></div></div>`
            );
            }

            let hostOpList = hostList.find(".opList");

            let liItem = hostOpList.find(`#${id}`);

            if(hostOpList.find("div").length >= 10){
                hostOpList.find("div").last().remove();
            }

            if(liItem.length > 0){
                // Some sort of race condition exists with the closing of a terminal
                // that emits a 103 / 105 status code after the 200 code so it causes
                // the operation list to say running even though the socket has closed
                // so this is a work around as im pretty once an event goes to 200 that
                // is it finished
                let span = liItem.find("span:eq(0)");
                if(span.data("status") == 200){
                    return;
                }

                liItem.html(`<span data-status='${msg.metadata.status_code}' class='${icon} mr-2'></span>${description}`);

                if(msg.metadata !== null && msg.metadata.hasOwnProperty("metadata") && msg.metadata.metadata !== null && msg.metadata.metadata.hasOwnProperty("download_progress")){
                    let progress = msg.metadata.metadata["download_progress"].replace("rootfs: ", "");
                    liItem.html(`<span data-status='${msg.metadata.status_code}' class='${icon} mr-2'></span>${description} - ${progress}`);
                }else if(msg.metadata !== null && msg.metadata.hasOwnProperty("metadata") && msg.metadata.metadata !== null && msg.metadata.metadata.hasOwnProperty("fs_progress")){
                    let progress = msg.metadata.metadata["fs_progress"];
                    liItem.html(`<span data-status='${msg.metadata.status_code}' class='${icon} mr-2'></span>${description} ${progress}`);
                }else if(msg.metadata !== null && msg.metadata.hasOwnProperty("metadata") && msg.metadata.metadata !== null && msg.metadata.metadata.hasOwnProperty("create_image_from_container_pack_progress")){
                    let progress = msg.metadata.metadata["create_image_from_container_pack_progress"].replace("Image pack:", "")
                    liItem.html(`<span data-status='${msg.metadata.status_code}' class='${icon} mr-2'></span>${description} ${progress}`);
                }

                if(msg.metadata.err !== ""){
                    $(liItem).data({
                        toggle: "tooltip",
                        placement: "bottom",
                        title: msg.metadata.err
                    }).addClass("btn-link text-danger").tooltip();
                }

            }else{
                hostOpList.prepend(makeOperationHtmlItem(id, icon, description, msg.metadata.status_code));
            }
        }
    }

    if(!currentSockets.hasOwnProperty(hostId)){
        currentSockets[hostId] = {};
    }

    currentSockets[hostId][project] = operationSocket;
}
