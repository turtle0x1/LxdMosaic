<style>
#terminalControls {
    position: absolute;z-index: 2;width: 20%;right: 0px;min-height: 5%;
}

#terminal-container {
    z-index: 1;
    position: absolute;
    width: 100%;
}

#terminalDiv {
    position: relative;
    background-color: black;
    padding-right: 0px;
    padding-left: 0px;
}

</style>

<div id="containerBox" class="boxSlide">
    <div class="row">
    <div class="col-md-12 text-center">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
              <div class="btn-toolbar">
                <div class="btn-group me-2">
                    <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Start Instance" class="btn btn-sm btn-success changeInstanceState" data-action="start">
                        <i class="fas fa-play"></i>
                    </button>
                    <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Stop Instance" class="btn btn-sm btn-danger changeInstanceState" data-action="stop">
                        <i class="fas fa-stop"></i>
                    </button>
                    <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Restart Instance" class="btn btn-sm btn-warning changeInstanceState" data-action="restart">
                        <i class="fa fa-sync"></i>
                    </button>
                    <hr/>
                    <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Freeze Instance" class="btn btn-sm btn-info changeInstanceState" data-action="freeze">
                        <i class="fas fa-snowflake"></i>
                    </button>
                    <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Unfreeze Instance" class="btn btn-sm btn-primary changeInstanceState" data-action="unfreeze">
                        <i class="fas fa-mug-hot"></i>
                    </button>
                </div>
              </div>

            <h4 class="pt-1">
                <span id="container-currentState"></span>
                <span id="container-containerNameDisplay"></span>
            </h4>
            <div class="btn-toolbar">
              <div class="btn-group me-2">
                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create Image" class="btn btn-sm btn-secondary" id="craeteImage">
                      <i class="fas fa-image"></i>
                  </button>
                  <hr/>
                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Copy Instance" class="btn btn-sm btn-info copyContainer">
                      <i class="fas fa-copy"></i>
                  </button>
                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Migrate Instance" class="btn btn-sm btn-primary migrateContainer">
                      <i class="fas fa-people-carry"></i>
                  </button>
                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Rename Instance" class="btn btn-sm btn-warning renameContainer">
                      <i class="fas fa-edit"></i>
                  </button>
                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete" class="btn btn-sm btn-danger deleteContainer">
                      <i class="fas fa-trash"></i>
                  </button>
              </div>
            </div>
        </div>
    </div>
    </div>
    <div class="row" id="containerViewBtns">
        <div class="col-md-12 text-center">
            <ul class="nav nav-tabs justify-content-center" id="containerBoxNav" style="border: none !important;">
                <li class="nav-item" data-view="containerDetails">
                    <div class="nav-link active" id="goToDetails">
                        <i class="fas fa-info-circle pe-2"></i>Details
                    </div>
                </li>
                <li class="nav-item" data-view="containerBackups">
                    <div class="nav-link" id="goToBackups">
                        <i class="fas fa-save pe-2"></i>Backups
                    </div>
                </li>
                <li class="nav-item" data-view="containerEvents">
                    <div class="nav-link" id="goToEvents">
                        <i class="fas fa-book-open pe-2"></i>Events
                    </div>
                </li>
                <li class="nav-item" data-view="containerFiles">
                    <div class="nav-link" id="goToFiles">
                        <i class="fas fa-folder pe-2"></i>File System
                    </div>
                </li>
                <li class="nav-item" data-view="containerMetrics">
                    <div class="nav-link" id="goToMetrics">
                        <i class="fas fa-chart-bar pe-2"></i>Metrics
                    </div>
                </li>
                <li class="nav-item" data-view="instancePackages">
                    <div class="nav-link" id="goToPackages">
                        <i class="fas fa-box pe-2"></i>Packages
                    </div>
                </li>
                <li class="nav-item" data-view="containerSnapshots">
                    <div class="nav-link" id="goToSnapshots">
                        <i class="fas fa-images pe-2"></i>Snapshots
                    </div>
                </li>
                <li class="nav-item" data-view="containerTerminal">
                    <div class="nav-link" id="goToTerminal">
                        <i class="fas fa-tv pe-2"></i>Terminal
                    </div>
                </li>
                <li class="nav-item" data-view="instanceTimers">
                    <div class="nav-link" id="goToTimers">
                        <i class="fas fa-hourglass-half pe-2"></i>Timers
                    </div>
                </li>
            </ul>
            <div class="btn-toolbar  mb-2 mb-md-0">

            </div>
        </div>
    </div>
<?php foreach (['containerDetails', 'containerTerminal', 'containerSnapshots', 'containerBackups', 'containerFiles', 'containerEvents', 'containerMetrics', 'packages', 'timers'] as $component) {
    require_once __DIR__ . '/boxComponents/instance/' . $component . '.html';
} ?>
</div>
<script src="/assets/dist/xterm.js"></script>
<script>

var term = null;
var fitAddon = new window.FitAddon.FitAddon()
var consoleSocket;
var currentTerminalProcessId = null;

function loadContainerTreeAfter(milSeconds = 2000, hostId = null, hostAlias = null)
{
    setTimeout(function(){
        let p = Number.isFinite(hostId) ? hostId : currentContainerDetails.hostId;
        let a = hostAlias == null ? currentContainerDetails.alias : hostAlias;
        addHostContainerList(p, a);
    }, milSeconds);
}

$(document).on("click", "#containerBoxNav > .nav-item", function(){
    if($(this).hasClass("disabled")){
        return false;
    }
    let view = $(this).data("view");
    let instanceName = currentContainerDetails.container;
    let hostAlias = currentContainerDetails.alias;
    
    if(view == "containerDetails"){
        router.navigate(`/instance/${hostAlias}/${instanceName}/details`)
    }else if(view == "containerBackups"){
        router.navigate(`/instance/${hostAlias}/${instanceName}/backups`)
    }else if(view == "containerEvents"){
        router.navigate(`/instance/${hostAlias}/${instanceName}/events`)
    }else if(view == "containerFiles"){
        router.navigate(`/instance/${hostAlias}/${instanceName}/files`)
    }else if(view == "containerMetrics"){
        router.navigate(`/instance/${hostAlias}/${instanceName}/metrics`)
    }else if(view == "instancePackages"){
        router.navigate(`/instance/${hostAlias}/${instanceName}/packages`)
    }else if(view == "containerSnapshots"){
        router.navigate(`/instance/${hostAlias}/${instanceName}/snapshots`)
    }else if(view == "containerTerminal"){
        router.navigate(`/instance/${hostAlias}/${instanceName}/terminal`)
    }else if(view == "instanceTimers"){
        router.navigate(`/instance/${hostAlias}/${instanceName}/timers`)
    }
});


</script>
<?php
    require __DIR__ . '/../modals/containers/migrateContainer.php';
require __DIR__ . '/../modals/containers/createContainer.php';
require __DIR__ . '/../modals/containers/editSettings.php';
require __DIR__ . '/../modals/containers/files/uploadFile.php';
require __DIR__ . '/../modals/instances/vms/createVm.php';
require __DIR__ . '/../modals/containers/createImage.php';
require __DIR__ . '/../modals/containers/assignProfiles.php';
require __DIR__ . '/../modals/instances/attachVolumes.php';
?>
