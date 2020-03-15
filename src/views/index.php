<?php
$haveServers = $this->container->make("dhope0000\LXDClient\Model\Hosts\HostList");

$userSession = $this->container->make("dhope0000\LXDClient\Tools\User\UserSession");

$isAdmin = (int) $userSession->isAdmin();



echo "<script>var userDetails = {isAdmin: $isAdmin} </script>";

if ($haveServers->haveAny() !== true) {
    header("Location: /views/firstRun");
    exit;
}
?>
<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v2.1.12
* @link https://coreui.io
* Copyright (c) 2018 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="en">
  <head>
      <script
          src="https://code.jquery.com/jquery-3.3.1.min.js"
          integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
          crossorigin="anonymous"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js" integrity="sha256-L3S3EDEk31HcLA5C6T2ovHvOcD80+fgqaCDt2BAi92o=" crossorigin="anonymous"></script>


      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

      <!-- <link rel="stylesheet" href="/assets/xterm/xterm.css" /> -->

      <script src="/assets/dist/external.dist.js" type="text/javascript" charset="utf-8"></script>

      <!-- Main styles for this application-->
      <link href="/assets/dist/external.dist.css" rel="stylesheet">

      <link rel="stylesheet" href="/assets/styles.css">

      <base href="./">
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <title>LXD Mosaic</title>

      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
      <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap.min.js"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

      <script src="/socket.io/socket.io.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js" integrity="sha256-Uv9BNBucvCPipKQ2NS9wYpJmi8DTOEfTA/nH2aoJALw=" crossorigin="anonymous"></script>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>

      <script src="/assets/lxdMosaic/globalFunctions.js"></script>
      <script>
          var currentContainerDetails = null;

          var globalUrls = {
              dashboard: {
                get: "/api/Dashboard/GetController/get"
              },
              instances: {
                  virtualMachines: {
                      create: "/api/Instances/VirtualMachines/CreateController/create"
                  }
              },
              //NOTE The url can't be "Analytics" because some ad blockers
              //     will block it by default
              analytics: {
                  getLatestData: "/api/AnalyticData/GetLatestDataController/get"
              },
              backups: {
                  getOverview: "/api/Backups/GetBackupsOverviewController/get",
                  restore: '/api/Backups/RestoreBackupController/restore'
              },
              clusters: {
                getAll: "/api/Clusters/GetAllController/get"
              },
              settings: {
                recordedActions: {
                    getLastResults: "/api/InstanceSettings/RecordedActions/GetLastController/get"
                },
                users: {
                    resetPassword: '/api/InstanceSettings/Users/ResetPasswordController/reset',
                    add: '/api/InstanceSettings/Users/AddUserController/add',
                    getAll: '/api/InstanceSettings/Users/GetUsersController/getAll',
                },
                getAll: "/api/InstanceSettings/GetAllSettingsController/getAll",
                saveAll: "/api/InstanceSettings/SaveAllSettingsController/saveAll"
              },
              networks: {
                  getAll: "/api/Networks/GetHostsNetworksController/get",
                  get: "/api/Networks/GetNetworkController/get",
                  deleteNetwork: "/api/Networks/DeleteNetworkController/delete",
                  createNetwork: "/api/Networks/CreateNetworkController/create"
              },
              storage: {
                  getAll: "/api/Storage/GetHostsStorageController/get",
                  getPool: "/api/Storage/GetHostsStoragePoolController/get",
                  deletePool: "/api/Storage/DeleteStoragePoolController/delete",
                  createPool: "/api/Storage/CreatePoolController/create"
              },
              deployments: {
                  create: "/api/Deployments/CreateController/create",
                  getAll: "/api/Deployments/GetController/getAll",
                  getDeployment: "/api/Deployments/GetDeploymentController/get",
                  getDeploymentConfigs: "/api/Deployments/GetCloudsConfigController/get",
                  deploy: "/api/Deployments/DeployController/deploy",
                  startDeployment: "/api/Deployments/StartDeploymentController/start",
                  stopDeployment: "/api/Deployments/StopDeploymentController/stop",
                  delete: "/api/Deployments/DeleteDeploymentController/delete",
              },
              profiles: {
                  search:{
                      getCommonProfiles: "/api/Profiles/Search/SearchProfiles/getAllCommonProfiles"
                  },
                  getAllProfiles: '/api/Profiles/GetAllProfilesController/getAllProfiles',
                  delete: '/api/Profiles/DeleteProfileController/delete',
                  rename: '/api/Profiles/RenameProfileController/rename',
                  copy: '/api/Profiles/CopyProfileController/copyProfile',
              },
              containers:{
                  create: "/api/Containers/CreateController/create",
                  delete: "/api/Containers/DeleteContainerController/deleteContainer",
                  getDetails: "/api/Containers/GetContainerDetailsController/get",
                  getCurrentSettings: "/api/Containers/GetCurrentContainerSettingsController/get",
                  migrate: "/api/Containers/MigrateContainerController/migrateContainer",
                  copy: "/api/Containers/CopyContainerController/copyContainer",
                  rename: "/api/Containers/RenameContainerController/renameContainer",
                  setSettings: "/api/Containers/SetSettingsController/set",
                  instanceTypes: {
                      getInstanceTypes: "/api/Containers/InstanceTypes/GetAllController/getAll"
                  },
                  state:{
                      startContainer: "/api/Containers/StateController/startContainer",
                      stopContainer: "/api/Containers/StateController/stopContainer",
                      restartContainer: "/api/Containers/StateController/restartContainer",
                      freezeContainer: "/api/Containers/StateController/freezeContainer",
                      unfreezeContainer: "/api/Containers/StateController/unfreezeContainer",
                  },
                  snapShots: {
                      take: "/api/Containers/Snapshot/TakeSnapshotController/takeSnapshot",
                      delete: "/api/Containers/Snapshot/DeleteSnapshotController/deleteSnapshot",
                      restore: "/api/Containers/Snapshot/RestoreSnapshotController/restoreSnapshot",
                      rename: "/api/Containers/Snapshot/RenameSnapshotController/renameSnapshot",
                      createFrom: "/api/Containers/CopyContainerController/copyContainer",
                  },
                  settings: {
                      getAllAvailableSettings: "/api/Containers/Settings/GetAllAvailableSettingsController/getAll",
                  },
                  backups: {
                      backup: "/api/Containers/Backups/BackupController/backup",
                      getContainerBackups: "/api/Containers/Backups/GetContainerBackupsController/get",
                      deleteContainerBackup: "/api/Containers/Backups/DeleteBackupController/delete",
                      importContainerBackup: "/api/Containers/Backups/ImportBackupController/import"
                  },
                  files: {
                      uploadFiles: '/api/Containers/Files/UploadFilesToPathController/upload',
                      delete: '/api/Containers/Files/DeletePathController/delete',
                      getPath: '/api/Containers/Files/GetPathController/get'
                  }
              },
              hosts: {
                  gpu: {
                    getAll: "/api/Hosts/GPU/GetAllController/getAll"
                  },
                  alias: {
                    update: "/api/Hosts/Alias/UpdateAliasController/update"
                  },
                  search: {
                      search: "/api/Hosts/SearchHosts/search"
                  },
                  containers: {
                      getAll: "/api/Hosts/Containers/GetAllController/getAll",
                      delete: "/api/Hosts/Containers/DeleteContainersController/delete",
                      getHostContainers: "/api/Hosts/Containers/GetHostsContainersController/get"
                  },
                  getAllHosts: "/api/Hosts/GetHostsController/getAllHosts",
                  getOverview: "/api/Hosts/GetOverviewController/get",
                  delete: "/api/Hosts/DeleteHostController/delete",
                  getHostOverview: "/api/Hosts/GetHostOverviewController/get"
              },
              images: {
                  search: {
                      searchAllHosts: "/api/Images/Search/SearchController/getAllAvailableImages",
                  },
                  getLinuxContainersOrgImages: "/api/Images/GetLinuxContainersOrgImagesController/get",
                  delete: "/api/Images/DeleteImagesController/delete",
                  getAll: "/api/Images/GetImagesController/getAllHostImages",
                  import: "/api/Images/ImportLinuxContainersByAliasController/import",
              },
              cloudConfig: {
                  search: {
                      searchAll: "/api/CloudConfig/Search/SearchController/searchAll"
                  },
                  create: '/api/CloudConfig/CreateController/create',
                  update: '/api/CloudConfig/UpdateController/update',
                  delete: '/api/CloudConfig/DeleteController/delete',
                  deploy: '/api/CloudConfig/DeployController/deploy',
                  getAll: '/api/CloudConfig/GetAllController/getAll',
                  getDetails: '/api/CloudConfig/GetDetailsController/get',
                  getAllFiles: '/api/CloudConfig/GetAllCloudConfigController/getAllConfigs'
              },
              user: {
                  setHostProject: '/api/User/SetSessionHostProjectController/set'
              },
              projects: {
                  create: '/api/Projects/CreateProjectController/create',
                  getAllFromHosts: '/api/Projects/GetHostsProjectsController/get',
                  info: '/api/Projects/GetProjectInfoController/get',
                  rename: '/api/Projects/RenameProjectController/rename',
                  delete: '/api/Projects/DeleteProjectController/delete',
              }
          };

          var statusCodeMap = {
              100: "OperationCreated",
              101: "Started",
              102: "Stopped",
              103: "Running",
              104: "Cancelling",
              105: "Pending",
              106: "Starting",
              107: "Stopping",
              108: "Aborting",
              109: "Freezing",
              110: "Frozen",
              111: "Thawed",
              112: "Error",
              200: "Success",
              400: "Failure",
              40: "Cancelled",
          }
          // TODO Sort these out
          var statusCodeIconMap = {
              100: "fa fa-ban",
              101: "fa fa-play",
              102: "fa fa-stop-circle",
              103: "fa fa-play",
              104: "fa fa-ban",
              105: "fa fa-clock",
              106: "fa fa-play",
              107: "fa fa-stop",
              108: "Aborting",
              109: "Freezing",
              110: "fas fa-snowflake",
              111: "Thawed",
              112: "fa fa-exclamation-triangle",
              200: "fa fa-check",
              400: "fa fa-exclamation-triangle",
              40:  "Cancelled",
          }

          toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": true,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
          }

          $(document).on("keyup", ".validateName", function(){
              this.value = this.value.replace(/[^-a-zA-Z0-9]+/g,'');
          })
      </script>
  </head>
  <body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
      <form style="display: none;" method="POST" id="downloadContainerFileForm" action="/api/Containers/Files/GetPathController/get">
          <input value="" name="hostId"/>
          <input value="" name="path"/>
          <input value="" name="container"/>
          <input value="1" type="number" name="download"/>

      </form>
    <header class="app-header navbar navbar-dark bg-dark">
      <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <i class="fas fa-bars" style="color: #dd4814;"></i>
      </button>
      <a class="navbar-brand" href="#">
        LXD Mosaic
      </a>
      <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <i class="fas fa-bars" style="color: #dd4814;"></i>
      </button>
      <ul class="navbar-nav mr-auto d-md-down-none" id="mainNav">
          <li class="nav-item active">
            <a class="nav-link overview createHostTre">
              <i class="fas fa-tachometer-alt"></i>
              <span class="hideNavText"> Dashboard </span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewProfiles">
              <i class="fas fa-users"></i>
              <span class="hideNavText"> Profiles </span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewClusters">
              <i class="far fa-object-group"></i>
              <span class="hideNavText"> Clusters </span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewCloudConfigFiles">
              <i class="fa fa-cogs"></i> <span class="hideNavText"> Cloud Config </span></a>
          </li>

          <li class="nav-item">
            <a class="nav-link viewImages">
              <i class="fa fa-images"></i> <span class="hideNavText"> Images </span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewProjects">
              <i class="fas fa-project-diagram"></i> <span class="hideNavText"> Projects </span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewDeployments">
              <i class="fas fa-rocket"></i> <span class="hideNavText"> Deployments </span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewStorage">
              <i class="fas fa-hdd"></i> <span class="hideNavText"> Storage </span> </a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewNetwork">
              <i class="fas fa-network-wired"></i> <span class="hideNavText"> Networks </span> </a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewBackups">
              <i class="fas fa-save"></i> <span class="hideNavText"> Backups </span> </a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewSettings">
              <i class="fas fa-wrench"></i> <span class="hideNavText"> Settings </span> </a>
          </li>
        </ul>
      <ul class="nav navbar-nav ml-auto d-md-down-none">
          <li class="nav-item px-3 btn btn-primary pull-right" id="addNewServer">
                <a> <i class="fas fa-plus"></i> Server </a>
           </li>
          <li class="nav-item px-3 btn btn-success pull-right" id="createContainer">
                <a> <i class="fas fa-plus"></i> Container </a>
           </li>
          <li class="nav-item px-3 btn btn-success pull-right" id="createVm">
                <a> <i class="fas fa-plus"></i> VM </a>
           </li>
      </ul>
    </header>
    <div class="app-body">
      <div class="sidebar">
        <nav class="sidebar-nav">
          <ul class="nav" id="sidebar-ul">

          </ul>
        </nav>
      </div>
      <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb" id="mainBreadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="container-fluid">
          <div id="dashboard" class="animated fadeIn">
            <div class="row">
            <div class="col-md-10" id="boxHolder">
                <?php
                    require __DIR__ . "/boxes/overview.php";
                    require __DIR__ . "/boxes/container.php";
                    require __DIR__ . "/boxes/profile.php";
                    require __DIR__ . "/boxes/cluster.php";
                    require __DIR__ . "/boxes/cloudConfig.php";
                    require __DIR__ . "/boxes/images.php";
                    require __DIR__ . "/boxes/projects.php";
                    require __DIR__ . "/boxes/deployments.php";
                    require __DIR__ . "/boxes/storage.php";
                    require __DIR__ . "/boxes/networks.php";
                    require __DIR__ . "/boxes/server.php";
                    require __DIR__ . "/boxes/backups.php";
                    require __DIR__ . "/boxes/settings.php";
                ?>
            </div>
            <div class="col-md-2">
                <div class="tree well" id="">
                <b> Operations </b>
                <div id="operationsList"></div>
                </div>
            </div>
            </div>
            <!-- /.row-->
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
<script type='text/javascript'>

$("#sidebar-ul").on("click", ".nav-item", function(){
    if($(this).hasClass("nav-dropdown")){
        return;
    }
    $("#sidebar-ul").find(".active").removeClass("active");
    $(this).addClass("active");
})

if(typeof io !== "undefined"){
    var socket = io.connect("/operations");

    socket.on('hostChange', function(msg){
        let data = $.parseJSON(msg);
        let status = data.offline ? "offline" : "online";
        makeServerChangePopup(status, data.host);
    });

    socket.on("deploymentProgress", function(msg){
        let tr = $("#deploymentContainersList").find("tr[data-deployment-container='" + msg.hostname + "']");
        if(tr.length > 0){
            $(tr).find("td:eq(5)").html(`<i class='fas fa-check'></i> ${moment().fromNow()}`);
        }

    });

    socket.on('operationUpdate', function(msg){
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
          loadContainerTreeAfter();
       }

       if(hostList.length == 0){
           $("#operationsList").append(`<div data-host='${host}'>
                <div class='text-center'>
                    <h5><u>
                        ${host}
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

           liItem.html(`<span data-status='${msg.metadata.status_code}' class='${icon}'></span>${description}`);
       }else{
           hostOpList.prepend(makeOperationHtmlItem(id, icon, description, msg.metadata.status_code));
       }
    });
}else {
    makeNodeMissingPopup();
}

$(".sidebar-nav").on("click", ".nav-item", function(){
    if(consoleSocket !== undefined && currentTerminalProcessId !== null){
        consoleSocket.emit("close", currentTerminalProcessId);
        currentTerminalProcessId = null;
    }
});

function makeOperationHtmlItem(id, icon, description, statusCode)
{
    return `<div id='${id}'><span data-status='${statusCode}' class='${icon}'></span>${description}</div>`;
}

var editor = ace.edit("editor");
  editor.setTheme("ace/theme/monokai");
  editor.getSession().setMode("ace/mode/yaml");

$(function(){
    Chart.defaults.global.defaultFontColor='white';
    $('[data-toggle="tooltip"]').tooltip({html: true})
    loadDashboard();
    $.contextMenu({
            selector: '.view-container',
            items: {
                "snapshot": {
                    name: "Snapshot",
                    icon: "fas fa-camera",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        snapshotContainerConfirm(item.data("hostId"), item.data("container"));
                    }
                },
                "copy": {
                    name: "Copy",
                    icon: "copy",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        copyContainerConfirm(item.data("hostId"), item.data("container"));
                    }
                },
                "edit": {
                    name: "Rename",
                    icon: "edit",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        renameContainerConfirm(item.data("hostId"), item.data("container"), false, item.data("alias"));
                    }
                },
                "delete": {
                    name: "Delete",
                    icon: "delete",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        deleteContainerConfirm(item.data("hostId"), item.data("alias"), item.data("container"));
                    }
                },
                "backup": {
                    name: "backup",
                    icon: "fas fa-save",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        backupContainerConfirm(item.data("hostId"), item.data("alias"), item.data("container"), null, false);
                    }
                },
            }
        });

        $.contextMenu({
            selector: '.filesystemObject',
            items: {
                "delete": {
                    name: "Delete",
                    icon: "delete",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        deleteFilesystemObjectConfirm(item.data("path"));
                    }
                }
            }
        });
        $.contextMenu({
            selector: '#filesystemTable',
            items: {
                "upload": {
                    name: "Upload",
                    icon: "upload",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        $("#modal-container-files-upload").modal("show");
                    }
                }
            }
        });
});

var unknownServerDetails = {
    cpu: {
        sockets: [{vendor: "Unknown Vendor"}],
        total: "Unknown Cpu Total"
    },
    memory: {
        used: "Uknown Memory Use",
        total: "Uknown Memory Total"
    }
};

function addHostContainerList(hostId, hostAlias) {
    ajaxRequest(globalUrls.hosts.containers.getHostContainers, {hostId: hostId}, (data)=>{
        data = makeToastr(data);
        let containers = "";
        if(Object.keys(data).length > 0){
            $.each(data, function(containerName, details){
                let active = "";
                if(currentContainerDetails !== null && currentContainerDetails.hasOwnProperty("container")){
                    if(hostId == currentContainerDetails.hostId && containerName == currentContainerDetails.container){
                        active = "active"
                    }
                }

                let typeFa = "box";

                if(details.info.hasOwnProperty("type") && details.info.type == "virtual-machine"){
                    typeFa = "vr-cardboard";
                }

                containers += `<li class="nav-item view-container ${active}"
                    data-host-id="${hostId}"
                    data-container="${containerName}"
                    data-alias="${hostAlias}">
                  <a class="nav-link" href="#">
                    <i class="nav-icon ${statusCodeIconMap[details.state.status_code]}"></i>
                    <i class="nav-icon fas fa-${typeFa}"></i>
                    ${containerName}
                  </a>
                </li>`;
            });
        }else {
            containers += `<li class="nav-item text-center text-warning">No Instances</li>`;
        }

        $("#sidebar-ul").find("li[data-hostid=" + hostId + "] ul").empty().append(containers);
    });
}

$(document).on("click", ".serverToggle", function(){
    let parentLi = $(this).parents("li");

    if(parentLi.hasClass("open")){
        parentLi.find("ul").empty();
        return;
    }

    let hostId = parentLi.data("hostid");
    let hostAlias = parentLi.data("alias");

    currentContainerDetails = null;

    loadServerView(hostId);
    addHostContainerList(hostId, hostAlias);
});

function loadDashboard(){
    $(".boxSlide").hide();
    $("#overviewBox").show();
    setBreadcrumb("Dashboard", "active overview");

    ajaxRequest(globalUrls.dashboard.get, {}, (data)=>{
        data = makeToastr(data);

        let hosts = `
            <li class="nav-item container-overview active">
                <a class="nav-link" href="#">
                    <i class="fas fa-tachometer-alt"></i> Overview
                </a>
            </li>`;

        let hostsTrs = "";

        $.each(data.clustersAndHosts.clusters, function(i, item){
            hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Cluster ${i}</u></li>`;

            hostsTrs += `<tr><td colspan="999" class="bg-success text-center text-white">Cluster ${i}</td></tr>`

            $.each(item.members, function(_, host){
                let disabled = "";

                if(host.status !== "Online"){
                    disabled = "disabled text-warning text-strikethrough";
                }

                let projects = "Not Available";

                if(host.resources.extensions.supportsProjects){
                    projects = "<select class='form-control changeHostProject'>";
                    $.each(host.resources.projects, function(o, project){
                        let selected = project == data.currentProject ? "selected" : "";
                            projects += `<option data-alias="${alias}" data-host='${data.hostId}'
                                value='${project}' ${selected}>
                                ${project}</option>`;
                    });
                    projects += "</select>";
                }

                let name = host.hostIpAndAlias.alias == null ? host.hostIpAndAlias.urlAndPort : host.hostIpAndAlias.alias;
                hostsTrs += `<tr data-host-id="${host.hostId}"><td><a data-id="${host.hostId}" class="viewHost" href="#">${name}</a></td><td>${projects}</td></tr>`

                hosts += `<li data-hostId="${host.hostId}" data-alias="${name}" class="nav-item containerList nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle serverToggle ${disabled}" href="#">
                        <i class="fas fa-server"></i> ${name}
                    </a>
                    <ul class="nav-dropdown-items">
                    </ul>
                </li>`;
            });
        });


        $("#currentMemoryUsageCardBody").empty().append('<canvas class="ml-auto mr-auto" id="currentMemoryUsageGraph" height="179"></canvas>');

        new Chart($("#currentMemoryUsageGraph"), {
            type: 'pie',
              data: {
                labels: ['Used', 'Free'],
                datasets: [{
                  label: 'Total Memory Used',
                  data: [data.stats.memory.used, (data.stats.memory.total - data.stats.memory.used)],
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

        hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Standalone Hosts</u></li>`;
        hostsTrs += `<tr><td colspan="999" class="bg-success text-center text-white">Standalone Hosts</td></tr>`

        $.each(data.clustersAndHosts.standalone, function(_, host){
            let disabled = "";

            let name = host.Host_Alias == null ? host.Host_Url_And_Port : host.Host_Alias;

            if(host.Host_Online == false){
                disabled = "disabled text-warning text-strikethrough";
            }

            let projects = "<b> Not Available </b>";


            if(host.Host_Online == 1 && host.resources.extensions.supportsProjects){
                projects = "<select class='form-control changeHostProject'>";
                $.each(host.resources.projects, function(o, project){
                    let selected = project == data.currentProject ? "selected" : "";
                        projects += `<option data-alias="${alias}" data-host='${data.hostId}'
                            value='${project}' ${selected}>
                            ${project}</option>`;
                });
                projects += "</select>";
            }
            hostsTrs += `<tr data-host-id="${host.Host_ID}"><td><a data-id="${host.Host_ID}" class="viewHost" href="#">${name}</a></td><td>${projects}</td></tr>`

            hosts += `<li data-hostId="${host.Host_ID}" data-alias="${name}" class="nav-item containerList nav-dropdown">
                <a class="nav-link nav-dropdown-toggle serverToggle ${disabled}" href="#">
                    <i class="fas fa-server"></i> ${name}
                </a>
                <ul class="nav-dropdown-items">
                </ul>
            </li>`;
        });
        $("#dashboardHostTable > tbody").empty().append(hostsTrs);
        $("#sidebar-ul").empty().append(hosts);

        if(data.analyticsData.hasOwnProperty("warning")){
            $("#memoryUsage, #activeContainers, #totalStorageUsage").hide().parents(".card-body").find(".notEnoughData").show();
            return false;
        }

        $("#memoryUsage, #activeContainers, #totalStorageUsage").show().parents(".card-body").find(".notEnoughData").hide();

        var mCtx = $('#memoryUsage');
        var acCtx = $('#activeContainers');
        var tsuCtx = $('#totalStorageUsage');

        let sum = data.analyticsData.activeContainers.data.reduce(getSum);

        let scaleStep = sum > 30 ? 10 : 1;

        new Chart(acCtx, {
            type: 'line',
            data: {
                datasets: [
                    {
                        label: "Fleet Active Containers",
                        borderColor: 'rgba(46, 204, 113, 1)',
                        pointBackgroundColor: "rgba(46, 204, 113, 1)",
                        pointBorderColor: "rgba(46, 204, 113, 1)",
                        data: data.analyticsData.activeContainers.data,
                    }
                ],
                labels: data.analyticsData.activeContainers.labels
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: scaleStep
                        }
                    }]
                }
            }
        });

        new Chart(mCtx, {
            type: 'line',
            data: {
                datasets: [
                    {
                        label: "Memory Usage",
                        borderColor: 'rgba(46, 204, 113, 1)',
                        pointBackgroundColor: "rgba(46, 204, 113, 1)",
                        pointBorderColor: "rgba(46, 204, 113, 1)",
                        data: data.analyticsData.memory.data,
                    }
                ],
                labels: data.analyticsData.memory.labels
            },
            options: {
                legend: {
                    display: false
                },
                scales: scalesBytesCallbacks,
                tooltips: toolTipsBytesCallbacks
            }
        });

        new Chart(tsuCtx, {
            type: 'line',
            data: {
                datasets: [
                    {
                        label: "Storage Usage",
                        borderColor: 'rgba(46, 204, 113, 1)',
                        pointBackgroundColor: "rgba(46, 204, 113, 1)",
                        pointBorderColor: "rgba(46, 204, 113, 1)",
                        data: data.analyticsData.storageUsage.data,
                    }
                ],
                labels: data.analyticsData.storageUsage.labels
            },
            options: {
                title: {
                    display: true,
                    text: 'Fleet Storage Usage'
                },
                legend: {
                    display: false
                },
                scales: scalesBytesCallbacks,
                tooltips: toolTipsBytesCallbacks
          }
        });
    });
}

$(document).on("click", ".viewHost", function(){
    let hostId = null;
    if($(this).hasClass("lookupId")){
        hostId = currentContainerDetails.hostId;
    }else{
        hostId = $(this).data("id");
    }

    loadServerView(hostId);
});

$("#sidebar-ul").on("click", ".view-container", function(){
    setContDetsByTreeItem($(this));
    loadContainerView(currentContainerDetails);
});

function setContDetsByTreeItem(node)
{
    currentContainerDetails = {
        container: node.data("container"),
        alias: node.data("alias"),
        hostId: node.data("hostId")
    }
    return currentContainerDetails;
}

$(document).on("click", "#addNewServer", function(){
    $("#modal-hosts-add").modal("show");
});

$(document).on("change", ".changeHostProject", function(){
    let selected = $(this).find(":selected");

    let x = {
        hostId: selected.parents("tr").data("hostId"),
        project: selected.val()
    };

    ajaxRequest(globalUrls.user.setHostProject, x, function(data){
        makeToastr(data);
        addHostContainerList(x.hostId, selected.data("alias"));
    })

});

$(document).on("click", ".overview, .container-overview", function(){
    $(".sidebar-fixed").addClass("sidebar-lg-show");
    currentContainerDetails = null;
    setBreadcrumb("Dashboard", "overview active");

    if($(this).hasClass("createHostTre")){
        loadDashboard();
    }

    changeActiveNav(".overview")
    $("#sidebar-ul").find(".active").removeClass("active");
    $("#sidebar-ul").find(".text-info").removeClass("text-info");
    $("#sidebar-ul").find(".container-overview").addClass("active");

    $(".boxSlide").hide();
    $("#overviewBox").show();
});

$(document).on("click", ".viewProfiles, .profile-overview", function(){
    setBreadcrumb("Profiles", "viewProfiles active");
    loadProfileView();
    $(".sidebar-fixed").addClass("sidebar-lg-show");
    changeActiveNav(".viewProfiles")
});

$(document).on("click", ".viewClusters, .cluster-overview", function(){
    setBreadcrumb("Clusters", "viewClusters active");
    loadClusterOverview();
    changeActiveNav(".viewClusters")
});

$(document).on("click", ".viewProjects, .projects-overview", function(){
    setBreadcrumb("Projects", "viewProjects active");
    loadProjectView();
    $(".sidebar-fixed").addClass("sidebar-lg-show");
    changeActiveNav(".viewProjects")
});

$(document).on("click", ".viewDeployments, .deployments-overview", function(){
    setBreadcrumb("Deployments", "viewDeployments active");
    loadDeploymentsView();
    $(".sidebar-fixed").addClass("sidebar-lg-show");
    changeActiveNav(".viewDeployments")
});

$(document).on("click", ".viewStorage, .storage-overview", function(){
    loadStorageView();
    $(".sidebar-fixed").addClass("sidebar-lg-show");
    changeActiveNav(".viewStorage")
});

$(document).on("click", ".viewNetwork, .network-overview", function(){
    loadNetworksView();
    $(".sidebar-fixed").addClass("sidebar-lg-show");
    changeActiveNav(".viewNetwork")
});

$(document).on("click", ".viewCloudConfigFiles, .cloudConfig-overview", function(){
    setBreadcrumb("Cloud Config", "viewCloudConfigFiles active");
    $(".sidebar-fixed").addClass("sidebar-lg-show");
    loadCloudConfigTree();
    changeActiveNav(".viewCloudConfigFiles")
});

$(document).on("click", ".viewSettings", function(){
    setBreadcrumb("Settings", "active");
    loadSettingsView();
    changeActiveNav(".viewSettings")
});

$(document).on("click", ".viewBackups", function(){
    setBreadcrumb("Backups", "active");
    loadBackupsView();
    changeActiveNav(".viewBackups")
});
</script>
<?php
    require_once __DIR__ . "/modals/hosts/addHosts.php";
    require_once __DIR__ . "/modals/images/import.php";
?>
</html>
