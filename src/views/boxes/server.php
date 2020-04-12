<div id="serverBox" class="boxSlide">
    <div id="serverOverview">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 id="serverHeading"></h1>
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
                </div>
            </div>
        </div>
        <div class="row border-bottom pb-2 mb-2">
                <div class="col-md-12 text-center justify-content">
                    <button type="button" class="btn text-white btn-outline-primary active" id="serverDetailsBtn" data-view="serverInfoBox">
                        <i class="fas fa-info pr-2"></i>Details
                    </button>
                    <button type="button" class="btn text-white btn-outline-primary" id="serverProxyDevicesBtn" data-view="serverProxyBox">
                        <i class="fas fa-exchange-alt pr-2"></i>Proxy Devices
                    </button>
                </div>
        </div>
        <div class="row serverViewBox" id="serverInfoBox">
        <div class="col-md-6">
            <div class="card bg-dark">
                <div class="card-header bg-dark">
                    <h4> Instances
                        <select id="serverContainerActions" class="form-control-sm float-right">
                            <option value="" selected></option>
                            <option value="start">Start</option>
                            <option value="stop">Stop</option>
                            <option value="delete">Delete</option>
                        </select>
                    </h4>

                </div>
                <div class="card-body table-responsive bg-dark">
                    <table id="containerTable" class="table table-dark table-bordered">
                        <thead>
                            <tr>
                                <td> <input type="checkbox" id="toggleAllContainers"> </td>
                                <td> Name </td>
                                <td> Disk Usage </td>
                                <td> Memory Usage </td>
                                <td> <a href="#" data-toggle="tooltip" data-placement="bottom" title="Excluding local interface bytes sent & received"> Network Usage </a> </td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-dark">
                        <div class="card-header">
                            Instance Stats
                        </div>
                        <div class="card-body">
                            <div id="serverInstancesOnlineBox">
                            </div>
                            <div class="alert alert-info" id="noContainersWarning">
                                There are no instances on the host
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="card bg-dark">
                        <div class="card-header">
                            Memory Stats
                        </div>
                        <div class="card-body">
                            <div id="serverMemoryUsageBox">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card bg-dark">
                        <div class="card-header">
                            <h4>CPU Details</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-dark" id="hostCpuTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Cores</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card bg-dark">
                        <div class="card-header">
                            <h4>GPU Details</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-dark" id="hostGpuTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>

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

    $("#containerTable").find(`tr:gt(${tr.index() + 1})`).each(function(){
        if($(this).hasClass("statusRow")){
            return false;
        }
        $(this).find("input[name=containerCheckbox]").prop("checked", checked);
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

$(document).on("click", "#serverDetailsBtn, #serverProxyDevicesBtn", function(){
    $("#serverDetailsBtn, #serverProxyDevicesBtn").removeClass("active")
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
    }
});

$(document).on("change", "#toggleAllContainers", function(){
    let checked = $(this).is(":checked");
    $("#containerTable").find("input[name=containerCheckbox]").each(function(){
        $(this).prop("checked", checked);
    });
});

$(document).on("change", "#serverContainerActions", function(){
    let action = $(this).val();
    if(action== ""){
        return false;
    }

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
        data = makeToastr(data);
        loadServerView(details.hostId);
        $("#serverContainerActions").find("option[value='']").prop("selected", true);
    });
});

$(document).on("click", "#editHost", function(){
    editHostDetailsObj.hostId = currentServer.hostId
    $("#modal-hosts-edit").modal("show");
});

function loadServerView(hostId)
{
    $(".boxSlide, #serverDetails, #serverProxyBox").hide();
    $("#serverOverview, #serverBox, #serverInfoBox").show();

    $("#serverDetailsBtn, #serverProxyDevicesBtn").removeClass("active");
    $("#serverDetailsBtn").addClass("active");

    $("#sidebar-ul").find(".active").removeClass("active");
    $("#sidebar-ul").find(".text-info").removeClass("text-info");
    $("#sidebar-ul").find(`[data-hostId='${hostId}'] > a:eq(0)`).addClass("text-info");

    currentServer.hostId = hostId;

    ajaxRequest(globalUrls.hosts.getHostOverview, {hostId: hostId}, (data)=>{
        data = $.parseJSON(data);

        let ident = data.header.alias == null ? data.header.urlAndPort : data.header.alias;

        addBreadcrumbs([ident], ["active"]);

        $("#serverHeading").text(ident);

        $("#serverContainerActions").find("option[value='']").prop("selected", true);

        let containerHtml, cpuTrs, gpuTrs = "";

        let cpuIndentKey = data.resources.extensions.resCpuSocket ? "name" : "vendor";

        $.each(data.resources.cpu.sockets, (_, item)=>{

            cpuTrs += `<tr>
                <td>${item[cpuIndentKey]}</td>
                <td>${$.isNumeric(item.cores) ? item.cores : item.cores.length}</td>
            </tr>`
        });

        $("#hostCpuTable > tbody").empty().append(cpuTrs);

        if(data.resources.extensions.resGpu && data.resources.hasOwnProperty("gpu") && data.resources.gpu.cards.length > 0){
            $.each(data.resources.gpu.cards, function(i, gpu){
                gpuTrs += `<tr><td>${gpu.product}</td></tr>`;
            });
        }else{
            gpuTrs += `<tr><td class="text-center">No GPU's</td></tr>`;
        }

        $("#hostGpuTable > tbody").empty().append(gpuTrs);



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

                    containerHtml += `<tr data-name="${name}">
                        <td><input name="containerCheckbox" type="checkbox"/></td>
                        <td>${name}</td>
                        <td>${storageUsage}</td>
                        <td>${formatBytes(details.state.memory.usage)}</td>
                        <td>R: ${formatBytes(bytesRecieved)} <br/> S: ${formatBytes(bytesSent)}</td>
                    </tr>`
                });
            });

            $("#serverInstancesOnlineBox").empty().append(`<canvas id="containerStatsChart" style="width: 100%;"></canvas>`);

            new Chart($("#containerStatsChart"), {
                type: 'pie',
                  data: {
                    labels: ['Online', 'Offline'],
                    datasets: [{
                      label: '# of containers',
                      data: [data.containerStats.online, data.containerStats.offline],
                      backgroundColor: [
                        'rgba(46, 204, 113, 1)',
                        'rgba(189, 195, 199, 1)'
                      ],
                      borderColor: [
                          'rgba(46, 204, 113, 1)',
                          'rgba(189, 195, 199, 1)'
                      ],
                      borderWidth: 1
                    }]
                  },
                  options: {
                   	cutoutPercentage: 40,
                    responsive: false,
                  }
            });
            $("#noContainersWarning").hide();
            $("#serverInstancesOnlineBox").show();
        }else{
            $("#serverInstancesOnlineBox").hide();
            $("#noContainersWarning").show();
            containerHtml = `<tr><td class="alert alert-info text-center" colspan="999">No Instances</td></tr>`
        }

        $("#containerTable > tbody").empty().append(containerHtml);

        $("#serverMemoryUsageBox").empty().append(`<canvas id="memoryStatsChart" style="width: 100%;"></canvas>`);

        new Chart($("#memoryStatsChart"), {
            type: 'pie',
              data: {
                labels: ['Used', 'Free'],
                datasets: [{
                  label: '# of containers',
                  data: [data.resources.memory.used, (data.resources.memory.total - data.resources.memory.used)],
                  backgroundColor: [
                    'rgba(46, 204, 113, 1)',
                    'rgba(189, 195, 199, 1)'
                  ],
                  borderColor: [
                      'rgba(46, 204, 113, 1)',
                      'rgba(189, 195, 199, 1)'
                  ],
                  borderWidth: 1
                }]
              },
              options: {
               	cutoutPercentage: 40,
                responsive: false,
                tooltips: toolTipsBytesCallbacks
              }
        });
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
