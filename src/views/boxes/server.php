<style>
    #serverHeaderInfo h5 {
        font-size: .8rem;
        margin-bottom: 0px;
    }
</style>
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
                <div class="row row-cols-1 row-cols-md-4 g-2 mb-2" id="serverHeaderInfo">
                    <div class="col">
                    <div class="card card-body text-center bg-dark text-white ps-1 pe-1">
                        <h5><i style="min-width: 10px" class="fas fa-microchip"></i><span id="serverCpuDisplay"></span></h5>
                    </div>
                    </div>
                    <div class="col">
                    <div class="card card-body text-center bg-dark text-white ps-1 pe-1">
                        <h5><i style="min-width: 10px; text-align: center;" class="fas fa-memory"></i><span class="ps-1" id="serverMemoryDisplay"></span> RAM</h5>
                    </div>
                    </div>
                    <div class="col">
                    <div class="card card-body text-center bg-dark text-white ps-1 pe-1">
                        <h5><i style="min-width: 10px; text-align: center;" class="fas fa-bolt"></i><span id="serverGpuDisplay"></span></h5>
                    </div>
                    </div>
                    <div class="col">
                    <div class="card card-body text-center bg-dark text-white ps-1 pe-1 enableIfAdmin" style="display: none;">
                        <h5><i style="min-width: 10px; text-align: center;" class="fas fa-hdd"></i><span id="serverDisksDisplay"></span></h5>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 pt-2 border-start">
                <div class="row pb-2 mb-2">
                        <div class="col-md-12 text-center justify-content" id="">
                            <ul class="nav nav-tabs nav-justified text-center" id="serverBoxNav" style="border: none !important;">
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
                                        <i class="fas fa-exclamation-triangle pe-2" style="color: black !important;"></i>Warnings<span id="newWarningsCount"></span>
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
                        <?php foreach (['serverInfoBox', 'serverInstanceBox', 'serverProxyBox', 'serverWarningsBox', 'serverSettingsBox', 'serverDiskBox'] as $component) {
                            require_once __DIR__ . '/boxComponents/server/' . $component . '.html';
                        } ?>
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

$(document).on("click", "#editHost", function(){
    editHostDetailsObj.supportsLoadAvgs = parseInt(currentServer.supportsLoadAvgs) ? true : false;
    editHostDetailsObj.hostAlias = currentServer.hostAlias
    editHostDetailsObj.hostId = currentServer.hostId
    $("#modal-hosts-edit").modal("show");
});


</script>

<?php
    require __DIR__ . '/../modals/hosts/instances/addProxyDevices.php';
require __DIR__ . '/../modals/hosts/editSettings.php';
?>
