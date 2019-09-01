<?php
$haveServers = $this->container->make("dhope0000\LXDClient\Model\Hosts\HostList");

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

      <link rel="stylesheet" href="/assets/xterm/xterm.css" />

      <!-- jqueryConfirm assets -->
      <link rel="stylesheet" href="/assets/jqueryConfirm/dist/jquery-confirm.min.css">
      <script src="/assets/jqueryConfirm/dist/jquery-confirm.min.js"></script>

      <!-- Ace web editor  -->
      <script src="/assets/ace/ace.js" type="text/javascript" charset="utf-8"></script>

      <!-- Main styles for this application-->
      <link href="/assets/coreui/style.css" rel="stylesheet">
      <script src="https://unpkg.com/@coreui/coreui/dist/js/coreui.min.js"></script>

      <link rel="stylesheet" href="/assets/styles.css">

      <link rel="stylesheet" href="/assets/toastr.js/toastr.min.css">
      <script src="/assets/toastr.js/toastr.min.js"></script>

      <base href="./">
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <title>LXD Mosaic</title>

      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
      <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap.min.js"></script>

      <script src="/assets/token/src/jquery.tokeninput.js"></script>
      <link rel="stylesheet" type="text/css" href="/assets/token/styles/token-input.css" />
      <link rel="stylesheet" type="text/css" href="/assets/token/styles/token-input-facebook.css" />

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
              //NOTE The url can't be "Analytics" because some ad blockers
              //     will block it by default
              analytics: {
                  getLatestData: "/api/AnalyticData/GetLatestDataController/get"
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
                  },
                  getAllHosts: "/api/Hosts/GetHostsController/getAllHosts",
                  getOverview: "/api/Hosts/GetOverviewController/get",
                  delete: "/api/Hosts/DeleteHostController/delete"
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
            <a class="nav-link overview">
              <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewProfiles">
              <i class="fas fa-users"></i> Profiles</a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewCloudConfigFiles">
              <i class="fa fa-cogs"></i> Cloud Config</a>
          </li>

          <li class="nav-item">
            <a class="nav-link viewImages">
              <i class="fa fa-images"></i> Images</a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewProjects">
              <i class="fas fa-project-diagram"></i> Projects</a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewDeployments">
              <i class="fas fa-rocket"></i> Deployments</a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewStorage">
              <i class="fas fa-hdd"></i> Storage </a>
          </li>
          <li class="nav-item">
            <a class="nav-link viewNetwork">
              <i class="fas fa-network-wired"></i> Networks </a>
          </li>
        </ul>
      <ul class="nav navbar-nav ml-auto d-md-down-none">
          <li class="nav-item px-3 btn btn-primary pull-right" id="addNewServer">
                <a> Add A Server </a>
           </li>
          <li class="nav-item px-3 btn btn-success pull-right" id="createContainer">
                <a> Create Container </a>
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
                    require __DIR__ . "/boxes/cloudConfig.php";
                    require __DIR__ . "/boxes/images.php";
                    require __DIR__ . "/boxes/projects.php";
                    require __DIR__ . "/boxes/deployments.php";
                    require __DIR__ . "/boxes/storage.php";
                    require __DIR__ . "/boxes/networks.php";
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
    $('[data-toggle="tooltip"]').tooltip({html: true})
    createContainerTree();
    loadServerOview();
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
                        renameContainerConfirm(item.data("hostId"), item.data("container"));
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

function loadServerOview()
{
    Chart.defaults.global.defaultFontColor='white';

    setBreadcrumb("Dashboard", "active overview");

    ajaxRequest(globalUrls.analytics.getLatestData, {}, function(data){
        data = $.parseJSON(data);

        if(data.hasOwnProperty("warning")){
            $("#memoryUsage, #activeContainers, #totalStorageUsage").hide().parents(".card-body").find(".notEnoughData").show();
            return false;
        }

        $("#memoryUsage, #activeContainers, #totalStorageUsage").show().parents(".card-body").find(".notEnoughData").hide();

        var mCtx = $('#memoryUsage');
        var acCtx = $('#activeContainers');
        var tsuCtx = $('#totalStorageUsage');

        let sum = data.activeContainers.data.reduce(getSum);

        let scaleStep = sum > 30 ? 10 : 1;

        new Chart(acCtx, {
            type: 'line',
            data: {
                datasets: [
                    {
                        label: "Fleet Active Containers",
                        borderColor: '#00aced',
                        pointBackgroundColor: "#ed4100",
                        pointBorderColor: "#ed4100",
                        data: data.activeContainers.data,
                    }
                ],
                labels: data.activeContainers.labels
            },
            options: {
                title: {
                    display: true,
                    text: 'Fleet Active Containers'
                },
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

        let toolTipsBytesCallbacks = {
            callbacks: {
                label: function(value, data) {
                    return formatBytes(value.value);
                }
            }
        };


        let scalesBytesCallbacks = {
          yAxes: [{
            ticks: {
              callback: function(value, index, values) {
                  return formatBytes(value);
              }
            }
          }]
        };

        new Chart(mCtx, {
            type: 'line',
            data: {
                datasets: [
                    {
                        label: "Memory Usage",
                        borderColor: '#00aced',
                        pointBackgroundColor: "#ed4100",
                        pointBorderColor: "#ed4100",
                        data: data.memory.data,
                    }
                ],
                labels: data.memory.labels
            },
            options: {
                title: {
                    display: true,
                    text: 'Fleet Memory Usage'
                },
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
                        borderColor: '#00aced',
                        pointBackgroundColor: "#ed4100",
                        pointBorderColor: "#ed4100",
                        data: data.storageUsage.data,
                    }
                ],
                labels: data.storageUsage.labels
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

    ajaxRequest(globalUrls.hosts.getOverview, null, function(data){
        let x = $.parseJSON(data);
        if(x.hasOwnProperty("error")){
            makeToastr(data);
            return false;
        }
        let html = "";
        $("#serverOverviewDetails").empty();

        $.each(x, function(host, data){


            let p = emptyServerBox();
            let indent = data.alias == "" ? host : data.alias + ` (${host})`;
            $(p).find(".host").text(indent);
            $(p).attr("id", data.hostId);

            if(data.online == false){
                $(p).find(".host").text(indent + " (Offline)");
                $(p).find(".bg-info").removeClass("bg-info").addClass("bg-danger");
                $(p).find(".deleteHost").removeClass("btn-danger").addClass("btn-info")
                $("#serverOverviewDetails").append(p);
                $(p).find(".brand-card-body").remove();
                return;
            }

            let memoryUsed = unknownServerDetails.memory.used;
            let memoryTotal = unknownServerDetails.memory.total;

            if(data.hasOwnProperty("memory")){
                memoryUsed = formatBytes(data.memory.used);
                memoryTotal = formatBytes(data.memory.total);
            }

            if(!data.hasOwnProperty("cpu")){
                data = unknownServerDetails;
            }

            if(data.extensions.supportsProjects){
                let projects = "";
                $.each(data.projects, function(o, project){
                    let selected = project == data.currentProject ? "selected" : "";
                        projects += `<option data-host='${data.hostId}'
                            value='${project}' ${selected}>
                            ${project}</option>`;
                });
                $(p).find(".projects").append(projects)
            }


            let cpuIndentKey = data.extensions.resCpuSocket ? "name" : "vendor";


            $(p).find(".memory").text(`${memoryUsed} / ${memoryTotal}`);
            $(p).find(".cpuDetails").text(`${data.cpu.sockets[0][cpuIndentKey]} - ${data.cpu.total} Cores`);

            if(data.extensions.resGpu && data.hasOwnProperty("gpu") && data.gpu.cards.length > 0){
                let g = "";
                $.each(data.gpu.cards, function(i, gpu){
                    g += `${gpu.product} <br/>`
                });

                $(p).find(".gpuDetails").html(g);
            }else{
                $(p).find(".gpuGroup").remove();
            }

            $("#serverOverviewDetails").append(p);
        });
        $(".boxSlide").hide();
        $("#overviewBox").show();
    });
}

function createContainerTree(){
    ajaxRequest(globalUrls.hosts.containers.getAll, {}, (data)=>{
        data = $.parseJSON(data);
        let treeData = [];
        let active = $.isPlainObject(currentContainerDetails) && currentContainerDetails.hasOwnProperty("container") ? "" : "active";
        let hosts = `
        <li class="nav-item container-overview ${active}">
            <a class="nav-link" href="#">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>`;
        $.each(data, function(i, host){
            let disabled = "";
            let listIsOpen = Object.keys(host.containers).length > 10 ? "" : "open";

            if(host.online == false){
                disabled = "disabled text-warning";
                i += " (Offline)";
            }

            hosts += `<li class="nav-item nav-dropdown ${listIsOpen}">
                <a class="nav-link nav-dropdown-toggle ${disabled}" href="#">
                    <i class="fas fa-server"></i> ${i}
                </a>
                <ul class="nav-dropdown-items">`;

            let containers = [];
            $.each(host.containers, function(containerName, details){
                let active = "";
                if(currentContainerDetails !== null && currentContainerDetails.hasOwnProperty("container")){
                    if(i == currentContainerDetails.alias && containerName == currentContainerDetails.container){
                        active = "active"
                    }
                }

                hosts += `<li class="nav-item view-container ${active}"
                    data-host-id="${host.hostId}"
                    data-container="${containerName}"
                    data-alias="${i}">
                  <a class="nav-link" href="#">
                    <i class="nav-icon ${statusCodeIconMap[details.state.status_code]}"></i>
                    ${containerName}
                  </a>
                </li>`;
            });

            hosts += `</ul>
                </li>`
        });
        $("#sidebar-ul").empty().append(hosts);
    });
}

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
        hostId: selected.data("host"),
        project: selected.val()
    };

    ajaxRequest(globalUrls.user.setHostProject, x, function(data){
        makeToastr(data);
        createContainerTree();
    })

});

$(document).on("click", ".overview, .container-overview", function(){
    currentContainerDetails = null;
    setBreadcrumb("Dashboard", "overview active");
    createContainerTree();
    loadServerOview();
    changeActiveNav(".overview")
    $(".boxSlide").hide();
    $("#overviewBox").show();
});

$(document).on("click", ".viewProfiles, .profile-overview", function(){
    setBreadcrumb("Profiles", "viewProfiles active");
    loadProfileView();
    changeActiveNav(".viewProfiles")
});

$(document).on("click", ".viewProjects, .projects-overview", function(){
    setBreadcrumb("Projects", "viewProjects active");
    loadProjectView();
    changeActiveNav(".viewProjects")
});

$(document).on("click", ".viewDeployments, .deployments-overview", function(){
    setBreadcrumb("Deployments", "viewDeployments active");
    loadDeploymentsView();
    changeActiveNav(".viewDeployments")
});

$(document).on("click", ".viewStorage, .storage-overview", function(){
    loadStorageView();
    changeActiveNav(".viewStorage")
});

$(document).on("click", ".viewNetwork, .network-overview", function(){
    loadNetworksView();
    changeActiveNav(".viewNetwork")
});

$(document).on("click", ".viewCloudConfigFiles, .cloudConfig-overview", function(){
    setBreadcrumb("Cloud Config", "viewCloudConfigFiles active");
    loadCloudConfigTree();
    changeActiveNav(".viewCloudConfigFiles")
});
</script>
<?php
    require_once __DIR__ . "/modals/hosts/addHosts.php";
    require_once __DIR__ . "/modals/images/import.php";
?>
</html>
