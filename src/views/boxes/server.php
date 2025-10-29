<div id="serverBox" class="boxSlide">
    <div id="serverOverview">
        <div class="row border-bottom">
            <div class="col-md-12">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-1">
                    <h1 class="mb-0"><i class="fas fa-server me-2"></i><span id="serverHeading">latest</span></h1>
                    <div class="btn-toolbar float-end enableIfAdmin" style="display: none;">
                      <div class="btn-group me-2">
                        <button class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit Host" id="editHost">
                            <i class="fas fa-wrench"></i>
                        </button>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 pt-2">
                <div class="mb-2">
                    <h5><i style="min-width: 20px" class="fas fa-microchip me-2"></i>CPU's</h5>
                    <div id="serverCpuDisplay">
                    </div>
                </div>
                <div class="mt-2 mb-2">
                    <h5><i style="min-width: 20px; text-align: center;" class="fas fa-memory me-2"></i>Memory</h5>
                    <div id="serverMemoryDisplay">
                    </div>
                </div>
                <div class="mt-2 mb-2">
                    <h5><i style="min-width: 20px; text-align: center;" class="fas fa-bolt me-2"></i>GPU's</h5>
                    <div id="serverGpuDisplay">

                    </div>
                </div>
                <div class="mt-2 mb-2 enableIfAdmin" style="display: none;">
                    <h5><i style="min-width: 20px; text-align: center;" class="fas fa-hdd me-2"></i>Disk's</h5>
                    <div id="serverDisksDisplay">

                    </div>
                </div>
                <div class="mt-2 mb-2 enableIfAdmin" style="display: none;">
                    <h5><i style="min-width: 20px; text-align: center;" class="fas fa-server me-2"></i>Hardware</h5>
                    <table class="table table-transparent" id="hostDetailsTable">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-9 pt-2 border-start">
                <div class="row pb-2 mb-2">
                        <div class="col-md-12 text-center justify-content" id="">
                            <ul class="nav nav-tabs text-center" id="serverBoxNav" style="border: none !important;">
                                <li class="nav-item" data-view="serverInfoBox">
                                    <div class="nav-link active" id="serverDetailsBtn">
                                        <i class="fas fa-tachometer-alt pe-2"></i>Overview
                                    </div>
                                </li>
                                <li class="nav-item" data-view="serverInstanceBox">
                                    <div class="nav-link " id="server">
                                        <i class="fas fa-box pe-2"></i>Instances
                                    </div>
                                </li>
                                <li class="nav-item" data-view="serverProxyBox">
                                    <div class="nav-link " id="serverProxyDevicesBtn">
                                        <i class="fas fa-exchange-alt pe-2"></i>Proxy Devices
                                    </div>
                                </li>
                                <li class="nav-item enableIfAdmin" data-view="serverDiskBox">
                                    <div class="nav-link " id="serverDiskBtn">
                                        <i class="fas fa-hdd pe-2" style="color: black !important;"></i>Disks
                                    </div>
                                </li>
                                <li class="nav-item enableIfAdmin" data-view="serverWarningsBox">
                                    <div class="nav-link " id="serverWarningsBtn">
                                        <i class="fas fa-exclamation-triangle pe-2" style="color: black !important;"></i>Warnings
                                    </div>
                                </li>
                                <li class="nav-item enableIfAdmin" data-view="serverSettingsBox">
                                    <div class="nav-link " id="serverSettingsBtn">
                                        <i class="fas fa-cog pe-2" style="color: black !important;"></i>Settings
                                    </div>
                                </li>
                            <ul>
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="serverInfoBox" class="serverViewBox">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-dark text-white text-center" id="newWarningCountCard" style="display: none;">
                                        <h1 id="newWarningsCount"></h1>
                                        <h4><i style="min-width: 20px; text-align: center; color: yellow !important;" class="fas fa-exclamation-triangle me-2"></i>New Warnings</h4>
                                    </div>
                                    <div class="card mt-2 bg-dark text-white text-center" id="memoryUsageCard">
                                        <div class="p-2 m-2 mb-0 pb-0" id="serverMemoryUsageBox"></div>
                                        <h4><i class="fas fa-memory me-2"></i>Memory</h4>
                                    </div>
                                    <div class="card mt-2 bg-dark text-white text-center" id="projectCountCard">
                                        <h1 id="projectsCount"></h1>
                                        <h4><i class="fas fa-project-diagram me-2"></i>Projects</h4>
                                    </div>
                                    <div class="card mt-2 bg-dark text-white text-center">
                                        <h1 id="containerCount"></h1>
                                        <h4><i class="fas fa-box me-2"></i>Containers</h4>
                                    </div>
                                    <div class="card mt-2 bg-dark text-white text-center">
                                        <h1 id="virtualMachineCount"></h1>
                                        <h4><i class="fas fa-vr-cardboard me-2"></i>Virutal Machines</h4>
                                    </div>
                                    <div class="card mt-2 bg-dark text-white text-center">
                                        <h1 id="networksCount"></h1>
                                        <h4><i class="fas fa-ethernet me-2"></i>Networks</h4>
                                    </div>
                                </div>
                                <div class="col-md-8" id="serverOverviewGraphs">
                                </div>
                            </div>
                        </div>
                        <div class="serverViewBox" id="serverInstanceBox">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-2">
                                    <h4><i class="fas fa-box me-2"></i>Instances</h4>
                                    <div class="btn-toolbar float-end">
                                      <div class="btn-group me-2">
                                        <button class="btn btn-success serverContainerActions" data-action="start" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Start Instances" disabled>
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <button class="btn btn-warning serverContainerActions" data-action="stop" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Stop Instances" disabled>
                                            <i class="fas fa-stop"></i>
                                        </button>
                                        <button class="btn btn-danger serverContainerActions" data-action="delete" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete Instances" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="serverInstanceTable" class="table table-dark table-bordered" style="vertical-align: middle">
                                    <thead>
                                        <tr>
                                            <td> <input type="checkbox" id="toggleAllContainers"> </td>
                                            <td> Instance </td>
                                            <td> Disk Usage </td>
                                            <td> Memory Usage </td>
                                            <td> IP Addresses </td>
                                            <td> <a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Excluding local interface bytes sent & received"> Network Usage </a> </td>
                                            <td> Gather Metrics</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="serverViewBox" id="serverProxyBox">
                            <div class="card bg-dark text-white">
                                <div class="card-header text-white">
                                    <h4>Proxy Devices
                                    <button class="btn btn-sm btn-primary float-end" id="addProxyDevice">
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
                        <div class="serverViewBox" id="serverWarningsBox">
                            <div class="card bg-dark text-white">
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
                        <div class="serverViewBox" id="serverSettingsBox">
                            <div class="card bg-dark text-white">
                                <div class="card-header text-white">
                                    <h4>Settings
                                        <button class="btn btn-outline-primary float-end" id="editHostSettings">
                                            <i class="fas fa-wrench"></i>
                                        </button>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-dark" id="serverSettingTable">
                                        <thead>
                                            <tr>
                                                <th>Setting</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="serverViewBox" id="serverDiskBox">
                            <div class="card bg-dark card-body">
                                <table class="table table-dark" id="serverDisksTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Device</th>
                                            <th>Model</th>
                                            <th>Type</th>
                                            <th>Read Only</th>
                                            <th>Mounted</th>
                                            <th>Size</th>
                                            <th>Removable</th>
                                            <th>Block Size</th>
                                            <th>RPM</th>
                                            <th>Device ID</th>
                                            <th>Partitions</th>
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
    </div>
</div>

<script>

var currentServer = {
    hostId: null
};


function loadHostOverview(req){
    currentContainerDetails = null;
    let hostId = req.data.hostId;
    currentServer.hostId = hostId
    currentServer.hostAlias = hostsAliasesLookupTable[hostId]
    createDashboardSidebar()

    loadServerView(hostId);
}

function _loadServerDetailsIfReq(hostId){
    if($("#serverCpuDisplay").text().trim() !== ""){
        return true;
    }
    ajaxRequest(globalUrls.hosts.getHostOverview, {hostId: hostId}, (data)=>{
        data = makeToastr(data);
        //TODO This is duplicate code, clear this up
        let ident = data.header.alias == null ? data.header.urlAndPort : data.header.alias;
        currentServer.hostAlias = data.header.alias;
        currentServer.supportsLoadAvgs = data.header.supportsLoadAvgs;

        router.updatePageLinks()

        $("#serverHeading").text(ident);

        let cpuHtml = "",
        hostDetailsTrs = "",
        gpuHtml = "";

        if(userDetails.isAdmin){
            $(".enableIfAdmin").show();
        }else{
            $(".enableIfAdmin").hide();
        }

        let cpuIndentKey = data.resources.extensions.resCpuSocket ? "name" : "vendor";

        $.each(data.resources.cpu.sockets, (_, item)=>{
            cpuHtml += `<div class="ps-2">
                ${item[cpuIndentKey]} - ${$.isNumeric(item.cores) ? item.cores : item.cores.length} Cores
            </div>`
        });

        $("#serverCpuDisplay").empty().append(cpuHtml);
        $("#serverMemoryDisplay").empty().append(`<div class="ps-2">${formatBytes(data.resources.memory.total)}</div>`);

        if(data.resources.extensions.resGpu && data.resources.hasOwnProperty("gpu") && data.resources.gpu.cards.length > 0){
            $.each(data.resources.gpu.cards, function(i, gpu){
                let name = gpu?.nvidia?.model ?? (gpu?.vendor ?? gpu.driver)  + " - " + (gpu?.product ?? gpu.driver_version)
                gpuHtml += `<div class="ps-2">${name}</div>`;
            });
        }else{
            gpuHtml += `<div class="ps-2">No GPU's</div>`;
        }

        $("#serverGpuDisplay").empty().append(gpuHtml);

        let disks = "";

        if(userDetails.isAdmin && data.resources.hasOwnProperty("storage") && data.resources.storage.hasOwnProperty("disks")){
            $.each(data.resources.storage.disks, (_, disk)=>{
                disks += `<div class="ps-2">${disk.model} - ${formatBytes(disk.size)}</div>`
            });
        }else{
            disks = `<div class="ps-2">Nothing To Display</div>`;
        }

        $("#serverDisksDisplay").empty().append(disks);

        if(data.resources.hasOwnProperty("hostDetails")){
            hostDetailsTrs += `
                <tr>
                    <td>OS</td>
                    <td>${data.resources.hostDetails.os_name}</td>
                </tr>
                <tr>
                    <td>OS Version</td>
                    <td>${data.resources.hostDetails.os_version}</td>
                </tr>
                <tr>
                    <td>Firewall</td>
                    <td>${data.resources.hostDetails.firewall}</td>
                </tr>
            `
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
    });
}

function loadHostSettings(req){
    currentContainerDetails = null;
    let hostId = req.data.hostId;
    currentServer.hostId = hostId
    currentServer.hostAlias = hostsAliasesLookupTable[hostId]
    createDashboardSidebar()
    _loadServerDetailsIfReq(hostId);
    changeActiveNav(".overview")
    $(".boxSlide, .serverViewBox").hide();
    $("#serverSettingsBox, #serverBox").show();

    addBreadcrumbs(["Dashboard", hostsAliasesLookupTable[hostId], "Settings"], ["", "", "active"], false, ["/", "", ""]);

    $("#serverBoxNav").find(".active").removeClass("active")
    $("#serverBoxNav .nav-item[data-view='serverSettingsBox'] > .nav-link").addClass("active")
    ajaxRequest('/api/Hosts/Settings/GetHostSettingsController/get', currentServer, (data)=>{
        data = makeToastr(data);
        let settingTrs = "";
        $.each(data, (key, value)=>{
            settingTrs += `<tr>
                <td>${key}</td>
                <td>${value}</td>
            </tr>`
        });
        $("#serverSettingTable > tbody").empty().append(settingTrs);
    });
}
function loadHostProxies(req){
    currentContainerDetails = null;
    let hostId = req.data.hostId;
    currentServer.hostId = hostId
    currentServer.hostAlias = hostsAliasesLookupTable[hostId]
    createDashboardSidebar()
    _loadServerDetailsIfReq(hostId);
    changeActiveNav(".overview")
    $(".boxSlide, .serverViewBox").hide();
    $("#serverProxyBox, #serverBox").show();

    addBreadcrumbs(["Dashboard", hostsAliasesLookupTable[hostId], "Proxy Devices"], ["", "", "active"], false, ["/", "", ""]);

    $("#serverBoxNav").find(".active").removeClass("active")
    $("#serverBoxNav .nav-item[data-view='serverProxyBox'] > .nav-link").addClass("active")

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

function loadHostDisks(req){
    currentContainerDetails = null;
    let hostId = req.data.hostId;
    currentServer.hostId = hostId
    currentServer.hostAlias = hostsAliasesLookupTable[hostId]
    createDashboardSidebar()
    _loadServerDetailsIfReq(hostId);
    changeActiveNav(".overview")
    $(".boxSlide, .serverViewBox").hide();
    $("#serverDiskBox, #serverBox").show();
    $("#serverBoxNav").find(".active").removeClass("active")
    $("#serverBoxNav .nav-item[data-view='serverDiskBox'] > .nav-link").addClass("active")
    ajaxRequest(globalUrls.hosts.getHostOverview, {hostId: hostId}, (data)=>{
        data = makeToastr(data)
        let diskTrs = "";
        data.resources.storage.disks.forEach(disk => {
            diskTrs += `<tr>
                <td>${disk.id}</td>
                <td>${disk.device}</td>
                <td>${disk.model}</td>
                <td>${disk.type}</td>
                <td>${disk.read_only ? `<i class="fas fa-check">` : `<i class="fas fa-times"></i>`}</td>
                <td>${disk.mounted ? `<i class="fas fa-check">` : `<i class="fas fa-times"></i>`}</td>
                <td>${formatBytes(disk.size)}</td>
                <td>${disk.removeable ? `<i class="fas fa-check">` : `<i class="fas fa-times"></i>`}</td>
                <td>${disk.block_size}</td>
                <td>${disk.rpm}</td>
                <td>${disk.device_id}</td>
                <td>${disk.partitions.length}</td>
            </tr>`
        });
        $("#serverDisksTable > tbody").empty().append(diskTrs)
    })
}

function loadHostWarnings(req){
    currentContainerDetails = null;
    let hostId = req.data.hostId;
    currentServer.hostId = hostId
    currentServer.hostAlias = hostsAliasesLookupTable[hostId]
    createDashboardSidebar()
    _loadServerDetailsIfReq(hostId);
    changeActiveNav(".overview")
    $(".boxSlide, .serverViewBox").hide();
    $("#serverWarningsBox, #serverBox").show();

    addBreadcrumbs(["Dashboard", hostsAliasesLookupTable[hostId], "Warnings"], ["", "", "active"], false, ["/", "", ""]);

    $("#serverBoxNav").find(".active").removeClass("active")
    $("#serverBoxNav .nav-item[data-view='serverWarningsBox'] > .nav-link").addClass("active")

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

function loadHostInstances(req){
    currentContainerDetails = null;
    let hostId = req.data.hostId;
    currentServer.hostId = hostId
    currentServer.hostAlias = hostsAliasesLookupTable[hostId]
    createDashboardSidebar()
    _loadServerDetailsIfReq(hostId);
    changeActiveNav(".overview")
    $(".boxSlide, .serverViewBox").hide();
    $("#serverInstanceBox, #serverBox").show();

    addBreadcrumbs(["Dashboard", hostsAliasesLookupTable[hostId], "Instances"], ["", "", "active"], false, ["/", "", ""]);

    $("#serverBoxNav").find(".active").removeClass("active")
    $("#serverBoxNav .nav-item[data-view='serverInstanceBox'] > .nav-link").addClass("active")

    ajaxRequest(globalUrls.hosts.instances.getHostContainers, {hostId: currentServer.hostId}, (data)=>{
        let instances = makeToastr(data)
        let instanceStatusesRows = {};

        if(Object.keys(instances).length > 0){
            $.each(instances, (name, instance)=>{
                if(!instanceStatusesRows[instance.status]){
                    instanceStatusesRows[instance.status] = `<tr class="statusRow">
                        <td class="text-center text-primary" colspan="999">
                            <i class="${instanceStatusesRows[instance.status]}"></i>
                            ${instance.status}
                            <input class="toggleStatusContainer" type="checkbox"/>
                        </td>
                    </tr>`
                }

                let storageUsage = instance.state.disk == null || instance.state.disk.length == 0 ? "N/A" : formatBytes(instance.state.disk.root.usage);

                let bytesSent = 0, bytesRecieved = 0;
                let ipAddresses = ``;
                $.each(instance.state.network, (networkName, network)=>{
                    if(networkName == "lo"){
                        return true;
                    }
                    $.each(network.addresses, (_, address)=>{
                        ipAddresses += `<div>${networkName}: <span>${address.address}</span></div>`
                    });
                    bytesSent += network.counters.bytes_sent;
                    bytesRecieved += network.counters.bytes_received;
                });

                let metricsButton = `<button data-host-id="${currentServer.hostId}" data-instance="${name}" class='btn btn-outline-primary btn-sm enableMetrics'>
                    Enable
                </button>`;

                if(instance.expanded_config.hasOwnProperty("environment.lxdMosaicPullMetrics")){
                    metricsButton = `<button data-host-id="${currentServer.hostId}" data-instance="${name}" class='btn btn-outline-warning btn-sm disableMetrics'>
                        Disable
                    </button>`;
                }

                instanceStatusesRows[instance.status] += `<tr data-name="${name}">
                    <td><input name="instanceCheckbox" type="checkbox"/></td>
                    <td>${name}</td>
                    <td>${storageUsage}</td>
                    <td>${formatBytes(instance.state.memory.usage)}</td>
                    <td>${ipAddresses}</td>
                    <td>R: ${formatBytes(bytesRecieved)} <br/> S: ${formatBytes(bytesSent)}</td>
                    <td>${metricsButton}</td>
                </tr>`
            });
        }else{
            instanceStatusesRows = `<tr><td class="text-center" colspan="999"><i class="fas fa-info-circle text-info me-2"></i>No instances!</td></tr>`
        }

        $("#serverInstanceTable > tbody").empty()

        if(typeof instanceStatusesRows == "string"){
            $("#serverInstanceTable > tbody").append(instanceStatusesRows);
        }else{
            let keys = Object.keys(instanceStatusesRows).sort();
            $.each(keys,  (_, key)=>{
                $("#serverInstanceTable > tbody").append(instanceStatusesRows[key])
            })
        }
    });
}

$(document).on("change", ".toggleStatusContainer", function(){
    let checked = $(this).is(":checked");
    let tr = $(this).parents("tr");

    if(checked){
        $(".serverContainerActions").attr("disabled", false);
        $("#serverInfoBox").find('[data-bs-toggle="tooltip"]').tooltip("enable")
    }else {
        $(".serverContainerActions").attr("disabled", true);
        $("#serverInfoBox").find('[data-bs-toggle="tooltip"]').tooltip("hide")
        $("#serverInfoBox").find('[data-bs-toggle="tooltip"]').tooltip("disable")
    }

    $("#serverInstanceTable").find(`tr:gt(${tr.index() + 1})`).each(function(){
        if($(this).hasClass("statusRow")){
            return false;
        }
        $(this).find("input[name=instanceCheckbox]").prop("checked", checked);
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

$(document).on("click", "#serverBoxNav > .nav-item", function(){
    if($(this).data("view") == "serverInfoBox"){
        router.navigate(`/host/${currentServer.hostAlias}/overview`)
    }else if($(this).data("view") == "serverInstanceBox"){
        router.navigate(`/host/${currentServer.hostAlias}/instances`)
    }else if($(this).data("view") == "serverProxyBox"){
        router.navigate(`/host/${currentServer.hostAlias}/proxies`)
    }else if($(this).data("view") == "serverWarningsBox"){
        router.navigate(`/host/${currentServer.hostAlias}/warnings`)
    }else if($(this).data("view") == "serverSettingsBox"){
        router.navigate(`/host/${currentServer.hostAlias}/settings`)
    }else if($(this).data("view") == "serverDiskBox"){
        router.navigate(`/host/${currentServer.hostAlias}/disks`)
    }
});

$(document).on("change", "input[name=instanceCheckbox]", function(){
    if($("input[name=instanceCheckbox]:checked").length > 0){
        $(".serverContainerActions").attr("disabled", false);
        $("#serverInstanceBox").find('[data-bs-toggle="tooltip"]').tooltip("enable")
    }else{
        $(".serverContainerActions").attr("disabled", true);
        $("#serverInstanceBox").find('[data-bs-toggle="tooltip"]').tooltip("hide")
        $("#serverInstanceBox").find('[data-bs-toggle="tooltip"]').tooltip("disable")
    }
});

$(document).on("click", "#toggleAllContainers", function(){
    let checked = $(this).is(":checked");

    if(checked){
        $(".serverContainerActions").attr("disabled", false);
        $("#serverInstanceBox").find('[data-bs-toggle="tooltip"]').tooltip("enable")
    }else {
        $(".serverContainerActions").attr("disabled", true);
        $("#serverInstanceBox").find('[data-bs-toggle="tooltip"]').tooltip("hide")
        $("#serverInstanceBox").find('[data-bs-toggle="tooltip"]').tooltip("disable")
    }

    $("#serverInstanceTable").find("input[name=instanceCheckbox]").each(function(){
        $(this).prop("checked", checked);
    });
});

$(document).on("click", ".ackWarning", function(){
    let warningId = $(this).parents("tr").attr("id");
    let td = $(this).parents("td");
    let btn = $(this);
    btn.attr("disabled", true);
    ajaxRequest(globalUrls.hosts.warnings.acknowledge, {hostId: currentServer.hostId, id: warningId}, (data)=>{
        data = makeToastr(data);
        if(data.hasOwnProperty("error") || data.state == "error"){
            btn.attr("disabled", false);
            return false;
        }
        td.empty().append("-");
    });
});

$(document).on("click", ".deleteWarning", function(){
    let tr = $(this).parents("tr");
    let warningId = tr.attr("id");
    let btn = $(this);
    btn.html("<i class='fas fa-cog fa-spin'></i>")
    tr.find("button").attr("disabled", true);
    ajaxRequest(globalUrls.hosts.warnings.delete, {hostId: currentServer.hostId, id: warningId}, (data)=>{
        data = makeToastr(data);
        if(data.hasOwnProperty("error") || data.state == "error"){
            btn.html("<i class='fas fa-trash'></i>")
            tr.find("button").attr("disabled", false);
            return false;
        }
        tr.remove();
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

    let checkboxes = $("#serverInstanceTable").find("input[name=instanceCheckbox]");

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
        $("#serverBoxNav").find("[data-view='serverInstanceBox']").trigger("click")
        $("#serverContainerActions").find("option[value='']").prop("selected", true);
        $("#serverInstanceBox").find('[data-bs-toggle="tooltip"]').tooltip("hide")
        $("#serverInstanceBox").find('[data-bs-toggle="tooltip"]').tooltip("disable")
    });
});

$(document).on("click", "#editHost", function(){
    editHostDetailsObj.supportsLoadAvgs = parseInt(currentServer.supportsLoadAvgs) ? true : false;
    editHostDetailsObj.hostAlias = currentServer.hostAlias
    editHostDetailsObj.hostId = currentServer.hostId
    $("#modal-hosts-edit").modal("show");
});

$(document).on("click", "#editHostSettings", function(){
    editHostSettingsDetailsObj.hostAlias = currentServer.hostAlias
    editHostSettingsDetailsObj.hostId = currentServer.hostId
    $("#modal-hosts-editSettings").modal("show");
});

function loadServerView(hostId)
{
    changeActiveNav(".overview")
    $(".boxSlide, .serverViewBox").hide();
    $("#serverOverview, #serverBox, #serverInfoBox").show();

    $("#serverBoxNav").find(".active").removeClass("active")
    $("#serverBoxNav .nav-item[data-view='serverInfoBox']").addClass("active")

    if(userDetails.isAdmin){
        $(".enableIfAdmin").show();
    }else{
        $(".enableIfAdmin").hide();
    }

    addBreadcrumbs(["Dashboard", hostsAliasesLookupTable[hostId]], ["", "active"], false, ["/", ""]);

    $("#serverNavBox").find(".active").removeClass("active");
    $("#serverDetailsBtn").addClass("active");

    $("#serverInfoBox").find('[data-bs-toggle="tooltip"]').tooltip("disable")

    currentServer.hostId = hostId;

    ajaxRequest(globalUrls.hosts.getHostOverview, {hostId: hostId}, (data)=>{
        data = makeToastr(data);

        let ident = data.header.alias == null ? data.header.urlAndPort : data.header.alias;
        currentServer.hostAlias = data.header.alias;
        currentServer.supportsLoadAvgs = data.header.supportsLoadAvgs;

        router.updatePageLinks()

        $("#serverHeading").text(ident);

        $(".serverContainerActions").attr("disabled", true)
        $("#toggleAllContainers").prop("checked", false);

        $("#projectsCount").text(data.resources.projects.length)

        function getLastKey(obj){
            return Object.keys(obj)[Object.keys(obj).length - 1];
        }

        if(Object.keys(data.projectAnalytics).length > 0){
            $("#containerCount").text(data.projectAnalytics.Containers[getLastKey(data.projectAnalytics.Containers)])
            $("#virtualMachineCount").text(data.projectAnalytics["Virtual Machines"][getLastKey(data.projectAnalytics["Virtual Machines"])])
            $("#networksCount").text(data.projectAnalytics["Networks"][getLastKey(data.projectAnalytics["Networks"])])
        }else{
            $("#containerCount").text("No Analytics / 0 Usage");
            $("#virtualMachineCount").text("No Analytics / 0 Usage");
            $("#networksCount").text("No Analytics / 0 Usage");
        }

        if(userDetails.isAdmin){
            let newWarnings = 0;
            $.each(data.warnings, (_, warning)=>{
                if(warning.status !== "acknowledged"){
                    newWarnings++;
                }
            });
            if(newWarnings > 0){
                $("#newWarningsCount").text(newWarnings)
                $("#newWarningCountCard").show();
                $("#memoryUsageCard").addClass("mt-2")
            }else{
                $("#newWarningCountCard").hide();
                $("#memoryUsageCard").removeClass("mt-2")
            }

        }else{
            $("#newWarningCountCard").hide();
            $("#memoryUsageCard").removeClass("mt-2")
        }

        let cpuHtml = "",
        hostDetailsTrs = "",
        gpuHtml = "";

        let cpuIndentKey = data.resources.extensions.resCpuSocket ? "name" : "vendor";

        $.each(data.resources.cpu.sockets, (_, item)=>{
            cpuHtml += `<div class="ps-2">
                ${item[cpuIndentKey]} - ${$.isNumeric(item.cores) ? item.cores : item.cores.length} Cores
            </div>`
        });

        $("#serverCpuDisplay").empty().append(cpuHtml);
        $("#serverMemoryDisplay").empty().append(`<div class="ps-2">${formatBytes(data.resources.memory.total)}</div>`);

        if(data.resources.extensions.resGpu && data.resources.hasOwnProperty("gpu") && data.resources.gpu.cards.length > 0){
            $.each(data.resources.gpu.cards, function(i, gpu){
                let name = gpu?.nvidia?.model ?? (gpu?.vendor ?? gpu.driver)  + " - " + (gpu?.product ?? gpu.driver_version)
                gpuHtml += `<div class="ps-2">${name}</div>`;
            });
        }else{
            gpuHtml += `<div class="ps-2">No GPU's</div>`;
        }

        $("#serverGpuDisplay").empty().append(gpuHtml);

        let disks = "";

        if(userDetails.isAdmin && data.resources.hasOwnProperty("storage") && data.resources.storage.hasOwnProperty("disks")){
            $.each(data.resources.storage.disks, (_, disk)=>{
                disks += `<div class="ps-2">${disk.model} - ${formatBytes(disk.size)}</div>`
            });
        }else{
            disks = `<div class="ps-2">Nothing To Display</div>`;
        }

        $("#serverDisksDisplay").empty().append(disks);

        if(data.resources.hasOwnProperty("hostDetails")){
            hostDetailsTrs += `
                <tr>
                    <td>OS</td>
                    <td>${data.resources.hostDetails.os_name}</td>
                </tr>
                <tr>
                    <td>OS Version</td>
                    <td>${data.resources.hostDetails.os_version}</td>
                </tr>
                <tr>
                    <td>Firewall</td>
                    <td>${data.resources.hostDetails.firewall}</td>
                </tr>
            `
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

        let instanceMemoryUsage  = 0;
        if(Object.keys(data.projectAnalytics).length > 0){
            instanceMemoryUsage = data.projectAnalytics["Memory"][getLastKey(data.projectAnalytics["Memory"])]
        }

        let memUsagePercent = ((data.resources.memory.used - instanceMemoryUsage) / data.resources.memory.total) * 100
        let instanceUsagePercent = (instanceMemoryUsage / data.resources.memory.total) * 100

        $("#serverMemoryUsageBox").empty().append(`<div class="mb-2">
            <div class="progress">
                <div data-bs-toggle="tooltip" data-bs-placement="bottom" title="Instances: ${formatBytes(instanceMemoryUsage)} (${instanceUsagePercent.toFixed(2)}%)" class="progress-bar bg-success" style="width: ${instanceUsagePercent}%" role="progressbar" aria-valuenow="${instanceMemoryUsage}" aria-valuemin="0" aria-valuemax="${(data.resources.memory.total - data.resources.memory.used)}"></div>
                <div data-bs-toggle="tooltip" data-bs-placement="bottom" title="System: ${formatBytes(data.resources.memory.used - instanceMemoryUsage)} (${memUsagePercent.toFixed(2)}%)" class="progress-bar bg-primary" style="width: ${memUsagePercent}%" role="progressbar" aria-valuenow="${data.resources.memory.used}" aria-valuemin="0" aria-valuemax="${(data.resources.memory.total - data.resources.memory.used)}"></div>
            </div>
        </div>`);

        $("#serverBox").find("[data-bs-toggle='tooltip']").tooltip();

        let displayItems = {
            "Instances": {
                formatBytes: false,
                icon: 'fas fa-box',
                cardClasses: '',
            },
            "Disk": {
                formatBytes: true,
                icon: 'fas fa-hdd',
                cardClasses: '',
            },
            "Memory": {
                formatBytes: true,
                icon: 'fas fa-memory',
                cardClasses: "mt-2"
            },
            "Processes": {
                formatBytes: false,
                icon: 'fas fa-microchip',
                cardClasses: "mt-2"
            }
        }
        $("#serverOverviewGraphs").empty();
        $("#overviewHistoryDurationBox").show()
        let y = $(`<div class="row mb-2" ></div>`)
        $.each(displayItems, (title, config) => {
            let labels = [];
            let values = [];
            let limits = [];

            let cId = title.toLowerCase();

            let totalUsage = 0;

            if(Object.keys(data.projectAnalytics).length > 0 ){
                let entries = data.projectAnalytics[title];
                let noOfEntries = Object.keys(entries).length;

                let dateFormat = noOfEntries == 13 || noOfEntries == 7 ? "HH:mm" : "ll HH:mm"

                $.each(entries, (created, value) => {
                    labels.push(moment.utc(created).local().format(dateFormat))
                    values.push(value)
                });

                totalUsage = values.reduce(function(a, b) {
                    a = a == null ? 0 : a
                    b = b == null ? 0 : b
                    return parseInt(a) + parseInt(b);
                }, 0);
            }else{
                totalUsage = 0;
            }


            let canvas = `<canvas height="250" id="${cId}"></canvas>`;

            if (totalUsage == 0) {
                canvas = '<div style="min-height: 250;" class="text-center "><i class="fas fa-info-circle  text-primary me-2"></i>No Usage</div>'
            }

            let x = $(`<div class='col-md-6 ${config.cardClasses}'>
                  <div class="card bg-dark text-white">
                      <div class="card-body">
                        <h4 class="mb-3 text-center"><i class="${config.icon} me-2"></i>${title}</h4>
                        ${canvas}
                      </div>
                  </div>
              </div>`);

            if (totalUsage > 0) {
                let graphDataSets = [{
                    label: "total",
                    borderColor: 'rgba(46, 204, 113, 1)',
                    pointBackgroundColor: "rgba(46, 204, 113, 1)",
                    pointBorderColor: "rgba(46, 204, 113, 1)",
                    data: values
                }];

                let options = {
                    responsive: true,
                    elements: {
                        point: {
                            // After 6 hours hide the dots on the graph
                            radius: 0
                        }
                    }
                };

                if (config.formatBytes) {
                    options.scales = scalesBytesCallbacks;
                    options.tooltips = toolTipsBytesCallbacks
                } else {
                    options.scales = {
                        yAxes: [{
                            ticks: {
                                precision: 0
                            }
                        }],
                    }
                }

                options.legend = {
                        display: false
                    },

                    // scales.yAxes.ticks
                    options.scales.yAxes[0].gridLines = {
                        color: "rgba(0, 0, 0, 0)",
                    }
                options.scales.yAxes[0].ticks.beginAtZero = false;
                options.scales.xAxes = [{
                    gridLines: {
                        color: "rgba(0, 0, 0, 0)",
                    },
                }]

                new Chart(x.find("#" + cId), {
                    type: 'line',
                    data: {
                        datasets: graphDataSets,
                        labels: labels
                    },
                    options: options
                });
            }
            y[0].append(x[0]);
        });
        $("#serverOverviewGraphs").append(y)
    });
}
</script>

<?php
    require __DIR__ . "/../modals/hosts/instances/addProxyDevices.php";
    require __DIR__ . "/../modals/hosts/editSettings.php";
?>
