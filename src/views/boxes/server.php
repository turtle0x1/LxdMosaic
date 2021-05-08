<div id="serverBox" class="boxSlide">
    <div id="serverOverview">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-1">
                    <h1><i class="fas fa-server mr-2"></i><span id="serverHeading"></span</h1>
                    <?php if ($isAdmin === 1) : ?>
                    <div class="btn-toolbar float-right">
                      <div class="btn-group mr-2">
                        <button class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Edit Host" id="editHost">
                            <i class="fas fa-cog"></i>
                        </button>
                        <button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete Host" id="deleteHost">
                            <i class="fas fa-trash"></i>
                        </button>
                      </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6 border-bottom">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h4><i class="fas fa-memory mr-2"></i>Server Memory Usage</h4>
                    <h4 id="serverMemoryUsagePercentage"></h4>
                </div>
                <div id="serverMemoryUsageBox"></div>
            </div>
            <div class="col-md-6 border-bottom">
                <div class="mb-3">
                    <h4><i class="fas fa-boxes mr-2"></i>Project Instances Online</h4>
                    <div id="serverInstancesOnlineBox"></div>
                </div>
            </div>
        </div>
        <div class="row border-bottom pb-2 mb-2">
                <div class="col-md-12 text-center justify-content">
                    <button type="button" class="btn text-white btn-outline-primary active" id="serverDetailsBtn" data-view="serverInfoBox">
                        <i class="fas fa-info pr-2"></i>Details
                    </button>
                    <button type="button" class="btn text-white btn-outline-primary" id="serverWarningsBtn" data-view="serverWarningsBox">
                        <i class="fas fa-exclamation-triangle pr-2" style="color: white !important;"></i>Warnings
                    </button>
                    <button type="button" class="btn text-white btn-outline-primary" id="serverProxyDevicesBtn" data-view="serverProxyBox">
                        <i class="fas fa-exchange-alt pr-2"></i>Proxy Devices
                    </button>
                </div>
        </div>
        <div class="row serverViewBox" id="serverInfoBox">
        <div class="col-md-8">
            <div class="card bg-dark">
                <div class="card-header d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
                    <h4><i class="fas fa-box mr-2"></i>Instances</h4>
                    <div class="btn-toolbar float-right">
                      <div class="btn-group mr-2">
                        <button class="btn btn-success serverContainerActions" data-action="start" data-toggle="tooltip" data-placement="bottom" title="Start Instances" disabled>
                            <i class="fas fa-play"></i>
                        </button>
                        <button class="btn btn-warning serverContainerActions" data-action="stop" data-toggle="tooltip" data-placement="bottom" title="Stop Instances" disabled>
                            <i class="fas fa-stop"></i>
                        </button>
                        <button class="btn btn-danger serverContainerActions" data-action="delete" data-toggle="tooltip" data-placement="bottom" title="Delete Instances" disabled>
                            <i class="fas fa-trash"></i>
                        </button>
                      </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="containerTable" class="table table-dark table-bordered">
                        <thead>
                            <tr>
                                <td> <input type="checkbox" id="toggleAllContainers"> </td>
                                <td> Name </td>
                                <td> Disk Usage </td>
                                <td> Memory Usage </td>
                                <td> <a href="#" data-toggle="tooltip" data-placement="bottom" title="Excluding local interface bytes sent & received"> Network Usage </a> </td>
                                <td> Gather Metrics</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card bg-dark">
                        <div class="card-header">
                            <h4><i class="fas fa-server mr-2"></i>Hardware</h4>
                        </div>
                        <div class="card-body">
                            <div>
                                <table class="table table-bordered table-dark" id="hostDetailsTable">
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="row serverViewBox" id="serverProxyBox">
            <div class="col-md-12">
                <div class="card bg-dark">
                    <div class="card-header text-white">
                        <h4>Proxy Devices
                        <button class="btn btn-sm btn-primary float-right" id="addProxyDevice">
                            <i class="fas fa-plus"></i>
                        </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-dark" id="serverProxyTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Source</th>
                                    <th>Destination</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row serverViewBox" id="serverWarningsBox">
            <div class="col-md-12">
                <div class="card bg-dark">
                    <div class="card-header text-white">
                        <h4>Warnings</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-dark" id="serverWarningTable">
                            <thead>
                                <tr>
                                    <th>Severity</th>
                                    <th>Type</th>
                                    <th>Last Message</th>
                                    <th>Status</th>
                                    <th>Acknowledge</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="serverDetails">
    </div>
</div>

<script>

var currentServer = {
    hostId: null
};

$(document).on("change", ".toggleStatusContainer", function(){
    let checked = $(this).is(":checked");
    let tr = $(this).parents("tr");

    if(checked){
        $(".serverContainerActions").attr("disabled", false);
        $("#serverInfoBox").find('[data-toggle="tooltip"]').tooltip("enable")
    }else {
        $(".serverContainerActions").attr("disabled", true);
        $("#serverInfoBox").find('[data-toggle="tooltip"]').tooltip("hide")
        $("#serverInfoBox").find('[data-toggle="tooltip"]').tooltip("disable")
    }

    $("#containerTable").find(`tr:gt(${tr.index() + 1})`).each(function(){
        if($(this).hasClass("statusRow")){
            return false;
        }
        $(this).find("input[name=containerCheckbox]").prop("checked", checked);
    });
});

$(document).on("click", ".disableMetrics", function(){
    let btn = $(this);
    let td = btn.parents("td");
    btn.attr("disabled", true);
    let x = btn.data();

    $.confirm({
        title: 'Disable Metric Gathering?!',
        content: `<div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="clearMetricdata">
          <label class="form-check-label" for="clearMetricdata">
            Clear Metric Data ?
          </label>
        </div>`,
        buttons: {
            cancel: function () {},
            yes: {
                btnClass: 'btn-danger',
                action: function () {
                    this.buttons.yes.setText('<i class="fa fa-cog fa-spin"></i>Deleting..'); // let the user know
                    this.buttons.yes.disable();
                    this.buttons.cancel.disable();
                    var modal = this;
                    x.clearData = modal.$content.find("#clearMetricdata").is(":checked") ? 1 : 0;
                    ajaxRequest(globalUrls.instances.metrics.disablePullGathering, x, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            btn.attr("disabled", false);
                            return false;
                        }
                        modal.close();
                        td.empty().append(`<button data-host-id="${x.hostId}" data-instance="${x.instance}" class='btn btn-outline-primary btn-sm enableMetrics'>
                            Enable
                        </button>`);
                    });
                    return false;
                }
            }
        }
    });

});

$(document).on("click", ".enableMetrics", function(){
    let btn = $(this);
    let td = btn.parents("td");
    btn.attr("disabled", true);
    let x = btn.data();

    ajaxRequest(globalUrls.instances.metrics.enablePullGathering, x, (data)=>{
        data = makeToastr(data);
        if(data.state == "error"){
            btn.attr("disabled", false);
            return false;
        }

        td.empty().append(`<button data-host-id="${x.hostId}" data-instance="${x.instance}" class='btn btn-outline-warning btn-sm disableMetrics'>
                    Disable
                </button>`);
    });
});

$(document).on("click", ".deleteProxy", function(){
    let btn = $(this);
    btn.attr("disabled", true);
    btn.html('<i class="fas fa-spinner fa-spin"></i>');
    let tr = btn.parents("tr");
    let x = $.extend(btn.data(), currentServer);
    ajaxRequest(globalUrls.hosts.instances.deleteProxyDevice, x, (data)=>{
        data = makeToastr(data)
        if(data.hasOwnProperty("state") && data.state == "success"){
            $("#serverProxyDevicesBtn").trigger("click");
        }else{
            btn.attr("disabled", false);
            btn.html('<i class="fas fa-trash"></i>');
        }
    });
});

$(document).on("click", "#addProxyDevice", function(){
    addProxyDeviceObj.hostId = currentServer.hostId;
    $("#modal-hosts-instnaces-addProxyDevice").modal("show");
});

$(document).on("click", "#serverDetailsBtn, #serverProxyDevicesBtn, #serverWarningsBtn", function(){
    $("#serverDetailsBtn, #serverProxyDevicesBtn, #serverWarningsBtn").removeClass("active")
    $(this).addClass("active")
    $(".serverViewBox").hide();
    $(`#${$(this).data("view")}`).show();

    if($(this).data("view") == "serverProxyBox"){
        ajaxRequest(globalUrls.hosts.instances.getAllProxyDevices, currentServer, (data)=>{
            data = makeToastr(data);

            let x = "";
            if(Object.keys(data).length){
                $.each(data, (container, proxies)=>{
                    x += `<tr><td colspan="999" class="bg-primary text-white text-center">${container}</td></tr>`
                    $.each(proxies, (name, device)=>{
                        x += `<tr>
                            <td>${name}</td>
                            <td>${device.listen}</td>
                            <td>${device.connect}</td>
                            <td>
                                <button
                                    class="btn btn-danger deleteProxy"
                                    data-instance="${container}"
                                    data-device="${name}"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`
                    });
                });
            }else{
                x += `<tr><td colspan="999" class="text-center text-info">No Proxy Devices</td></tr>`
            }

            $("#serverProxyTable > tbody").empty().append(x);
        });
    }else if($(this).data("view") == "serverWarningsBox"){
        ajaxRequest(globalUrls.hosts.warnings.getOnHost, currentServer, (data)=>{
            data = makeToastr(data);

            let x = "";
            if(Object.keys(data).length){
                $.each(data, (_, warning)=>{
                    let ackButton = '-';
                    if(warning.status != "acknowledged"){
                        ackButton = `<button class="btn btn-success ackWarning">
                            <i class="fas fa-check" style="color: white !important;"></i>
                        </button>`
                    }
                    x += `<tr id="${warning.uuid}">
                        <td>${warning.severity}</td>
                        <td>${warning.type}</td>
                        <td>${warning.last_message}</td>
                        <td>${warning.status}</td>
                        <td>
                            ${ackButton}
                        </td>
                        <td>
                            <button class="btn btn-danger deleteWarning">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`
                });
            }else{
                x += `<tr><td colspan="999" class="text-center text-info">No Warnings</td></tr>`
            }

            $("#serverWarningTable > tbody").empty().append(x);
        });
    }
});

$(document).on("change", "input[name=containerCheckbox]", function(){
    if($("input[name=containerCheckbox]:checked").length > 0){
        $(".serverContainerActions").attr("disabled", false);
        $("#serverInfoBox").find('[data-toggle="tooltip"]').tooltip("enable")
    }else{
        $(".serverContainerActions").attr("disabled", true);
        $("#serverInfoBox").find('[data-toggle="tooltip"]').tooltip("hide")
        $("#serverInfoBox").find('[data-toggle="tooltip"]').tooltip("disable")
    }
});

$(document).on("click", "#toggleAllContainers", function(){
    let checked = $(this).is(":checked");

    if(checked){
        $(".serverContainerActions").attr("disabled", false);
        $("#serverInfoBox").find('[data-toggle="tooltip"]').tooltip("enable")
    }else {
        $(".serverContainerActions").attr("disabled", true);
        $("#serverInfoBox").find('[data-toggle="tooltip"]').tooltip("hide")
        $("#serverInfoBox").find('[data-toggle="tooltip"]').tooltip("disable")
    }

    $("#containerTable").find("input[name=containerCheckbox]").each(function(){
        $(this).prop("checked", checked);
    });
});

$(document).on("click", ".serverContainerActions", function(){
    let action = $(this).data("action");
    if(action== ""){
        return false;
    }

    let btn = $(this);

    let origHtml = btn.html();

    btn.html("<i class='fas fa-cog fa-spin'></i>");

    let checkboxes = $("#containerTable").find("input[name=containerCheckbox]");

    let selectedContainers = checkboxes.filter(":checked").map(function () {
        return $(this).parents("tr").data("name");
    }).get();


    if(selectedContainers.length == 0){
        $.alert("Please select atleast one container");
        return false;
    }

    let details = {
        hostId: currentServer.hostId,
        containers: selectedContainers
    };

    let url = globalUrls.hosts.instances[action]

    ajaxRequest(url, details, (data)=>{
        btn.html(origHtml);
        data = makeToastr(data);
        loadServerView(details.hostId);
        $("#serverContainerActions").find("option[value='']").prop("selected", true);
        $("#serverInfoBox").find('[data-toggle="tooltip"]').tooltip("hide")
        $("#serverInfoBox").find('[data-toggle="tooltip"]').tooltip("disable")
    });
});

$(document).on("click", "#editHost", function(){
    editHostDetailsObj.supportsLoadAvgs = parseInt(currentServer.supportsLoadAvgs) ? true : false;
    editHostDetailsObj.hostAlias = currentServer.hostAlias
    editHostDetailsObj.hostId = currentServer.hostId
    $("#modal-hosts-edit").modal("show");
});

function loadServerView(hostId)
{
    $(".boxSlide, #serverDetails, #serverProxyBox").hide();
    $("#serverOverview, #serverBox, #serverInfoBox").show();

    $("#serverDetailsBtn, #serverProxyDevicesBtn").removeClass("active");
    $("#serverDetailsBtn").addClass("active");

    $("#serverInfoBox").find('[data-toggle="tooltip"]').tooltip("disable")

    $("#sidebar-ul").find(".active").removeClass("active");
    $("#sidebar-ul").find(".text-info").removeClass("text-info");
    $("#sidebar-ul").find(`[data-hostId='${hostId}'] > a:eq(0)`).addClass("text-info");

    currentServer.hostId = hostId;

    ajaxRequest(globalUrls.hosts.getHostOverview, {hostId: hostId}, (data)=>{
        data = $.parseJSON(data);

        let ident = data.header.alias == null ? data.header.urlAndPort : data.header.alias;
        currentServer.hostAlias = data.header.alias;
        currentServer.supportsLoadAvgs = data.header.supportsLoadAvgs;
        addBreadcrumbs([ident], ["active"]);

        $("#serverHeading").text(ident);

        $(".serverContainerActions").attr("disabled", true)
        $("#toggleAllContainers").prop("checked", false);

        let containerHtml, hostDetailsTrs = "";

        let cpuIndentKey = data.resources.extensions.resCpuSocket ? "name" : "vendor";

        hostDetailsTrs += `<tr><td colspan="999" class="bg-info text-center text-white"><i class="fas fa-microchip mr-2"></i>CPU'S</td></tr>`

        $.each(data.resources.cpu.sockets, (_, item)=>{
            hostDetailsTrs += `<tr>
                <td>${item[cpuIndentKey]}</td>
                <td>${$.isNumeric(item.cores) ? item.cores : item.cores.length} Cores</td>
            </tr>`
        });

        hostDetailsTrs += `<tr><td colspan="999" class="bg-info text-center text-white"><i class="fas fa-bolt mr-2"></i>GPU'S</td></tr>`

        if(data.resources.extensions.resGpu && data.resources.hasOwnProperty("gpu") && data.resources.gpu.cards.length > 0){
            $.each(data.resources.gpu.cards, function(i, gpu){
                hostDetailsTrs += `<tr><td colspan="999">${gpu.product}</td></tr>`;
            });
        }else{
            hostDetailsTrs += `<tr><td colspan="999" class="text-center">No GPU's</td></tr>`;
        }

        if(data.resources.hasOwnProperty("system")){

            let mbProduct = "Not Set";
            let chasisType = "Not Set";
            let firmwareDetails = "Not Set";
            let uuid = "Not Set";
            let type = "Not Set";

            if(data.resources.system != null){
                type = data.resources.system.type;
                uuid = data.resources.system.uuid;

                if(data.resources.system.motherboard != null){
                    mbProduct = data.resources.system.motherboard.product;
                }

                if(data.resources.system.chassis != null){
                    chasisType = data.resources.system.chassis.type
                }

                if(data.resources.system.firmware  != null){
                    firmwareDetails = `${data.resources.system.firmware.vendor}
                    -
                    ${data.resources.system.firmware.version}
                    (${data.resources.system.firmware.date})`;
                }
            }


            hostDetailsTrs += `
                <tr>
                    <td colspan="999" class="bg-info text-center text-white"><i class="fas fa-info-circle mr-2"></i>System Details</td>
                </tr>
                <tr>
                    <td>Motherboard</td>
                    <td>${mbProduct}</td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td>${type}</td>
                </tr>
                <tr>
                    <td>Chasis Type</td>
                    <td>${chasisType}</td>
                </tr>
                <tr>
                    <td>System Firmware</td>
                    <td>
                        ${firmwareDetails}
                    </td>
                </tr>
                <tr>
                    <td>UUID</td>
                    <td>${uuid}</td>
                </tr>
            `;
        }

        $("#hostDetailsTable > tbody").empty().append(hostDetailsTrs);



        if(Object.keys(data.containers).length > 0){
            $.each(data.containers, function(state, containers){
                containerHtml += `<tr class="statusRow">
                    <td class="text-center bg-info" colspan="999">
                        <i class="${statusCodeIconMap[containers[Object.keys(containers)[0]].state.status_code]}"></i>
                        ${state}
                        <input class="toggleStatusContainer" type="checkbox"/>
                    </td>
                </tr>`;
                $.each(containers, function(name, details){

                    let storageUsage = details.state.disk == null || details.state.disk.length == 0 ? "N/A" : formatBytes(details.state.disk.root.usage);

                    let bytesSent = 0, bytesRecieved = 0;

                    $.each(details.state.network, (networkName, network)=>{
                        if(networkName == "lo"){
                            return true;
                        }

                        bytesSent += network.counters.bytes_sent;
                        bytesRecieved += network.counters.bytes_received;
                    });

                    let metricsButton = `<button data-host-id="${currentServer.hostId}" data-instance="${name}" class='btn btn-outline-primary btn-sm enableMetrics'>
                        Enable
                    </button>`;

                    if(details.expanded_config.hasOwnProperty("environment.lxdMosaicPullMetrics")){
                        metricsButton = `<button data-host-id="${currentServer.hostId}" data-instance="${name}" class='btn btn-outline-warning btn-sm disableMetrics'>
                            Disable
                        </button>`;
                    }

                    containerHtml += `<tr data-name="${name}">
                        <td><input name="containerCheckbox" type="checkbox"/></td>
                        <td>${name}</td>
                        <td>${storageUsage}</td>
                        <td>${formatBytes(details.state.memory.usage)}</td>
                        <td>R: ${formatBytes(bytesRecieved)} <br/> S: ${formatBytes(bytesSent)}</td>
                        <td>${metricsButton}</td>
                    </tr>`
                });
            });

            let instanceStatsWidth = ((data.containerStats.online / (data.containerStats.online + data.containerStats.offline)) * 100)
            $("#serverInstancesOnlineBox").empty().append(`<div class="mb-2">
                <div class="progress">
                    <div data-toggle="tooltip" data-placement="bottom" title="${data.containerStats.online}" class="progress-bar bg-success" style="width: ${instanceStatsWidth}%" role="progressbar" aria-valuenow="${data.containerStats.online}" aria-valuemin="0" aria-valuemax="${(data.containerStats.online + data.containerStats.offline)}"></div>
                </div>
            </div>`);
        }else{
            $("#serverInstancesOnlineBox").empty().append(`<div class="text-center"><i class="fas fa-info-circle text-info mr-2"></i>No instances!</div>`);
            containerHtml = `<tr><td class="text-center" colspan="999"><i class="fas fa-info-circle text-info mr-2"></i>No instances!</td></tr>`
        }

        $("#containerTable > tbody").empty().append(containerHtml);

        let memoryWidth = ((data.resources.memory.used / data.resources.memory.total) * 100)
        $("#serverMemoryUsageBox").empty().append(`<div class="mb-2">
            <div class="progress">
                <div data-toggle="tooltip" data-placement="bottom" title="${formatBytes(data.resources.memory.used)}" class="progress-bar bg-success" style="width: ${memoryWidth}%" role="progressbar" aria-valuenow="${data.resources.memory.used}" aria-valuemin="0" aria-valuemax="${(data.resources.memory.total - data.resources.memory.used)}"></div>
            </div>
        </div>`);

        let memUsagePercent = (data.resources.memory.used / data.resources.memory.total) * 100;

        $("#serverMemoryUsagePercentage").text(`${parseFloat(memUsagePercent).toFixed(2)}%`);

        $("#serverBox").find("[data-toggle='tooltip']").tooltip();
    });
}

$(document).on("click", "#deleteHost", function(){
    let hostId = $(this).parents(".brand-card").attr("id");
    $.confirm({
        title: 'Delete Host',
        content: 'Are you sure you want to remove this host!',
        buttons: {
            cancel: function () {},
            yes: {
                btnClass: 'btn-danger',
                action: function () {
                    this.buttons.yes.setText('<i class="fa fa-cog fa-spin"></i>Deleting..'); // let the user know
                    this.buttons.yes.disable();
                    this.buttons.cancel.disable();
                    var modal = this;
                    ajaxRequest(globalUrls.hosts.delete, {hostId: currentServer.hostId}, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            return false;
                        }
                        location.reload();
                    });
                    return false;
                }
            }
        }
    });
});
</script>

<?php
    require __DIR__ . "/../modals/hosts/instances/addProxyDevices.php";
?>
