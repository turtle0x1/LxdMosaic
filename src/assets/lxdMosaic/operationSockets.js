var currentEventSocket = null;

function makeOperationHtmlItem(id, icon, description, statusCode, timestamp)
{
    return `<div class="op row pt-2 pb-2" style="border-bottom: 1px dashed grey" id='${id}' data-timestamp="${timestamp}" data-status='${statusCode}' ><div class="col-md-1"><span style='display: flex; align-items: center; height: 100%' class='${icon} me-2'></span></div><div class="col-md-11">${description}</div></div>`;
}

// Called in a internval in the index.php page
function clearOldOperations()
{
    let minutesAgo = moment().subtract("5", "minutes");
    $("#operationsList").find(".op").each(function(){
        if([200, 400, 401].includes($(this).data("status"))){
            let t = moment($(this).data("timestamp"));
            if(t.isBefore(minutesAgo)){
                $(this).remove();
            }
        }
    });
}

function calcRunningOps()
{
    let total = 0;
    $("#operationsList").find(".op").each(function(){
        if(parseInt($(this).data("status")) === 103){
            total++;
        }
    });
    let badge = $("#operationsDropdownButton").find(".badge");

    if(total == 0){
        badge.remove()
    }

    if(badge.length > 0){
        badge.text(`${total}`)
    }else{
        $("#operationsDropdownButton").append(`<span class="badge bg-secondary">${total}</span>`)
        $("#operationsList").append(`<li id="noOps"><div class="dropdown-item" href="#"><i class="fas fa-info-circle text-info me-2"></i>No Operations In Progress</div></li>`)
    }

}

function openHostOperationSocket(hostId, project)
{
    let f = ()=>{
        if(currentEventSocket !== null && currentEventSocket.readyState === 1) {
            currentEventSocket.send(JSON.stringify({
                hostId: hostId,
                project: project,
            }))
        }else{
            setTimeout(f, 50);
        }
    }
    setTimeout(f, 50);
}

function openEventSocket()
{
    if(currentEventSocket !== null){
        return true;
    }

    currentEventSocket = new WebSocket(`wss://${getQueryVar("host", window.location.hostname)}:${getQueryVar("port", 443)}/node/operations?ws_token=${userDetails.apiToken}&user_id=${userDetails.userId}`);

    currentEventSocket.onclose = (msg) => {
        currentEventSocket = null;
    }

    currentEventSocket.onmessage = (msg) => {
        msg = JSON.parse(msg.data);

        if(msg.type == "lifecycle"){
            if(lifecycleCallbacks.hasOwnProperty(msg.metadata.action)){
                lifecycleCallbacks[msg.metadata.action](msg)
            }
            return false;
        }

        $("#noOps").remove();

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
            let timestamp = msg.timestamp;
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
                <div class=''>
                <i class="fas fa-server me-2"></i><b>${msg.hostAlias}</b>
                </div>
                <div class='opList'></div></div>`
            );
            }

            let hostOpList = hostList.find(".opList");

            let liItem = hostOpList.find(`#${id}`);

            if(hostOpList.find("div").length >= 10){
                hostOpList.find("div").last().remove();
            }

            if(msg.metadata !== null &&  msg.metadata.hasOwnProperty("resources") && msg.metadata.resources !== null){
                if(msg.metadata.resources.hasOwnProperty("instances")){
                    if(msg.metadata.resources.hasOwnProperty("instances")){
                        description += " on " + msg.metadata.resources.instances.map(instance => {
                            return instance.replace("/1.0/instances/", "")
                        }).join(",")
                    }
                }
            }

            if(msg.metadata !== null && msg.metadata.hasOwnProperty("metadata") && msg.metadata.metadata !== null && msg.metadata.metadata.hasOwnProperty("download_progress")){
                description += " " + msg.metadata.metadata["download_progress"].replace("rootfs: ", "");
            }else if(msg.metadata !== null && msg.metadata.hasOwnProperty("metadata") && msg.metadata.metadata !== null && msg.metadata.metadata.hasOwnProperty("fs_progress")){
                description += " " + msg.metadata.metadata["fs_progress"];
            }else if(msg.metadata !== null && msg.metadata.hasOwnProperty("metadata") && msg.metadata.metadata !== null && msg.metadata.metadata.hasOwnProperty("create_image_from_container_pack_progress")){
                description += " " + msg.metadata.metadata["create_image_from_container_pack_progress"].replace("Image pack:", "")
            }else if(msg.metadata !== null && msg.metadata.hasOwnProperty("metadata") && msg.metadata.metadata !== null && msg.metadata.metadata.hasOwnProperty("create_backup_progress")){
                description += " " + msg.metadata.metadata["create_backup_progress"]
            }else if(msg.metadata !== null && msg.metadata.hasOwnProperty("metadata") && msg.metadata.metadata !== null && msg.metadata.metadata.hasOwnProperty("command")){
                description += ` <div><code class="text-info"><i class='fas fa-terminal me-2'></i>${msg.metadata.metadata.command.join(",")}</code></div>`;
            }

            if(msg.metadata !== null){
                if(msg.metadata.hasOwnProperty("err") && msg.metadata.err != ""){
                    description += ` <div><code class="text-danger"><i class='fas fa-exclamation me-2'></i>${msg.metadata.err}</code></div>`;
                }
                if(msg.metadata.hasOwnProperty("created_at")){
                    description += ` <div><code style="color: #d63384"><i class='fas fa-calendar-alt me-2'></i>${moment(msg.metadata.created_at).format("llll")}</code></div>`;
                }
            }


            if(liItem.length > 0){
                // Some sort of race condition exists with the closing of a terminal
                // that emits a 103 / 105 status code after the 200 code so it causes
                // the operation list to say running even though the socket has closed
                // so this is a work around as im pretty once an event goes to 200 that
                // is it finished
                if(liItem.data("status") == 200){
                    return;
                }

                liItem.replaceWith(makeOperationHtmlItem(id, icon, description, msg.metadata.status_code, timestamp))

                if(msg.metadata.err !== ""){
                    liItem.attr("data-bs-toggle", "popover")
                    .attr("data-bs-placement", "bottom")
                    .attr("data-bs-content", msg.metadata.err)
                    .attr("data-bs-trigger", "hover")
                    .attr("data-title", "Error")
                    .addClass("btn-link text-danger")
                    .popover()
                }
            }else{
                hostOpList.prepend(makeOperationHtmlItem(id, icon, description, msg.metadata.status_code, timestamp));
            }
            calcRunningOps()
        }
    }
}
