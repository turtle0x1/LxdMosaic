<?php
$haveServers = $this->container->make("dhope0000\LXDClient\Model\Hosts\HostList");

$userSession = $this->container->make("dhope0000\LXDClient\Tools\User\UserSession");
$validatePermissions = $this->container->make("dhope0000\LXDClient\Tools\User\ValidatePermissions");
$getSetting = $this->container->make("dhope0000\LXDClient\Model\InstanceSettings\GetSetting");
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

$recordActionsEnabled = $getSetting->getSettingLatestValue(InstanceSettingsKeys::RECORD_ACTIONS);
$siteTitle = $getSetting->getSettingLatestValue(InstanceSettingsKeys::SITE_TITLE);

$userId = $userSession->getUserId();
$isAdmin = (int) $validatePermissions->isAdmin($userId);
$apiToken = $userSession->getToken();


echo "<script>
var userDetails = {
    isAdmin: $isAdmin,
    apiToken: '$apiToken',
    userId: $userId
}
var recordActionsEnabled = parseInt($recordActionsEnabled);
</script>";

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
      <link rel="apple-touch-icon" sizes="57x57" href="/assets/lxdMosaic/favicons/apple-icon-57x57.png">
      <link rel="apple-touch-icon" sizes="60x60" href="/assets/lxdMosaic/favicons/apple-icon-60x60.png">
      <link rel="apple-touch-icon" sizes="72x72" href="/assets/lxdMosaic/favicons/apple-icon-72x72.png">
      <link rel="apple-touch-icon" sizes="76x76" href="/assets/lxdMosaic/favicons/apple-icon-76x76.png">
      <link rel="apple-touch-icon" sizes="114x114" href="/assets/lxdMosaic/favicons/apple-icon-114x114.png">
      <link rel="apple-touch-icon" sizes="120x120" href="/assets/lxdMosaic/favicons/apple-icon-120x120.png">
      <link rel="apple-touch-icon" sizes="144x144" href="/assets/lxdMosaic/favicons/apple-icon-144x144.png">
      <link rel="apple-touch-icon" sizes="152x152" href="/assets/lxdMosaic/favicons/apple-icon-152x152.png">
      <link rel="apple-touch-icon" sizes="180x180" href="/assets/lxdMosaic/favicons/apple-icon-180x180.png">
      <link rel="icon" type="image/png" sizes="192x192"  href="/assets/lxdMosaic/favicons/android-icon-192x192.png">
      <link rel="icon" type="image/png" sizes="32x32" href="/assets/lxdMosaic/favicons/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="96x96" href="/assets/lxdMosaic/favicons/favicon-96x96.png">
      <link rel="icon" type="image/png" sizes="16x16" href="/assets/lxdMosaic/favicons/favicon-16x16.png">
      <link rel="manifest" href="/assets/lxdMosaic/favicons/manifest.json">
      <meta name="msapplication-TileColor" content="#ffffff">
      <meta name="msapplication-TileImage" content="/assets/lxdMosaic/favicons/ms-icon-144x144.png">
      <meta name="theme-color" content="#ffffff">

      <script src="/assets/dist/external.dist.js" type="text/javascript" charset="utf-8"></script>

      <!-- <link rel="stylesheet" href="/assets/xterm/xterm.css" /> -->

      <!-- Main styles for this application-->
      <link href="/assets/dist/external.dist.css" rel="stylesheet">

      <link rel="stylesheet" href="/assets/styles.css">

      <base href="./">
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <title><?= $siteTitle ?></title>

      <link rel="stylesheet" href="/assets/dist/external.fontawesome.css">

      <script src="/assets/lxdMosaic/globalFunctions.js"></script>
      <script src="/assets/lxdMosaic/globalDetails.js"></script>
      <script src="/assets/lxdMosaic/operationSockets.js"></script>
      <script>
          var currentContainerDetails = null;

          var globalUrls = {
              universe: {
                getEntities: '/api/Universe/GetEntitiesFromUniverseController/get'
              },
              dashboard: {
                  get: "/api/Dashboard/GetController/get"
              },
              instances: {
                  volumes: {
                    assign: "/api/Instances/Volumes/AttachVolumesController/attach"
                  },
                  comment: {
                      set: "/api/Instances/Comments/SetInstanceCommentController/set",
                  },
                  profiles: {
                    remove: "/api/Instances/Profiles/RemoveProfileController/remove",
                    assign: "/api/Instances/Profiles/AssignProfilesController/assign",
                  },
                  metrics: {
                      getAllAvailableMetrics: "/api/Instances/Metrics/GetAllAvailableMetricsController/get",
                      getGraphData: "/api/Instances/Metrics/GetGraphDataController/get",
                      getAllTypes: "/api/Instances/Metrics/GetGraphDataController/getAllTypes",
                      getTypeFilters: "/api/Instances/Metrics/GetGraphDataController/getTypeFilters",
                      enablePullGathering: "/api/Instances/Metrics/EnablePullGatheringController/enable",
                      disablePullGathering: "/api/Instances/Metrics/DisablePullGatheringController/disable",
                  },
                  virtualMachines: {
                      create: "/api/Instances/VirtualMachines/CreateController/create"
                  },
                  state:{
                      start: "/api/Instances/StateController/start",
                      stop: "/api/Instances/StateController/stop",
                      restart: "/api/Instances/StateController/restart",
                      freeze: "/api/Instances/StateController/freeze",
                      unfreeze: "/api/Instances/StateController/unfreeze",
                  },
                  files: {
                      uploadFiles: '/api/Instances/Files/UploadFilesToPathController/upload',
                      delete: '/api/Instances/Files/DeletePathController/delete',
                      getPath: '/api/Instances/Files/GetPathController/get'
                  },
                  snapShots: {
                      take: "/api/Instances/Snapshot/TakeSnapshotController/takeSnapshot",
                      delete: "/api/Instances/Snapshot/DeleteSnapshotController/deleteSnapshot",
                      restore: "/api/Instances/Snapshot/RestoreSnapshotController/restoreSnapshot",
                      rename: "/api/Instances/Snapshot/RenameSnapshotController/renameSnapshot",
                      createFrom: "/api/Instances/CopyInstanceController/copy",
                  },
                  backups: {
                      delete: "/api/Instances/Backups/DeleteLocalBackupController/delete",
                      disabledSchedule: "/api/Instances/Backups/DisableScheduledBackupsController/disable",
                      schedule: "/api/Instances/Backups/ScheduleBackupController/schedule",
                      backup: "/api/Instances/Backups/BackupController/backup",
                      getContainerBackups: "/api/Instances/Backups/GetInstanceBackupsController/get",
                      deleteContainerBackup: "/api/Instances/Backups/DeleteBackupController/delete",
                      importContainerBackup: "/api/Instances/Backups/ImportBackupController/import"
                  },
                  instanceTypes: {
                      providers: {
                          add: '/api/Instances/InstanceTypes/Providers/AddController/add',
                          removeProvider: '/api/Instances/InstanceTypes/Providers/RemoveController/remove'
                      },
                      addInstanceType: "/api/Instances/InstanceTypes/AddController/add",
                      getInstanceTypes: "/api/Instances/InstanceTypes/GetAllController/getAll",
                      deleteInstanceType: "/api/Instances/InstanceTypes/DeleteController/delete"
                  },
                  settings: {
                      getAllAvailableSettings: "/api/Instances/Settings/GetAllAvailableSettingsController/getAll",
                  },
                  delete: "/api/Instances/DeleteInstanceController/delete",
                  getInstance: "/api/Instances/GetInstanceController/get",
                  rename: "/api/Instances/RenameInstanceController/rename",
                  copy: "/api/Instances/CopyInstanceController/copy",
                  migrate: "/api/Instances/MigrateInstanceController/migrate",
                  getCurrentSettings: "/api/Instances/GetCurrentInstanceSettingsController/get",
                  setSettings: "/api/Instances/SetSettingsController/set",
                  create: "/api/Instances/CreateController/create",
                  exportImage: "/api/Instances/CreateImageController/create",
              },
              //NOTE The url can't be "Analytics" because some ad blockers
              //     will block it by default
              analytics: {
                  getAllData: '/api/AnalyticData/DownloadHistoryController/download'
              },
              backups: {
                  strategies: {
                    getAll: "/api/Backups/Strategies/GetStrategiesController/get",
                  },
                  getOverview: "/api/Backups/GetBackupsOverviewController/get",
                  restore: '/api/Backups/RestoreBackupController/restore'
              },
              clusters: {
                getCluster: "/api/Clusters/GetClusterController/get"
              },
              settings: {
                ldap: {
                    save: "/api/InstanceSettings/Ldap/SaveLdapSettingsController/save",
                },
                recordedActions: {
                    getLastResults: "/api/InstanceSettings/RecordedActions/GetLastController/get"
                },
                users: {
                    allowedProjects: {
                        grantAcess: '/api/User/AllowedProjects/GrantAccessController/grant',
                        grantAcessToProject: '/api/User/AllowedProjects/GrantAccessToProjectController/grant',
                        revokeAccess: '/api/User/AllowedProjects/RevokeAccessController/revoke',
                        getAllowed: '/api/User/AllowedProjects/GetUserAllowedProjectsController/get'
                    },
                    resetPassword: '/api/InstanceSettings/Users/ResetPasswordController/reset',
                    add: '/api/InstanceSettings/Users/AddUserController/add',
                    getAll: '/api/InstanceSettings/Users/GetUsersController/getAll',
                    search: '/api/InstanceSettings/Users/SeachUsersController/search',
                    toggleAdminStatus: '/api/User/ToggleAdminStatusController/toggle',
                    toggleLoginStatus: '/api/User/ToggleLoginStatusController/toggle'
                },
                getAll: "/api/InstanceSettings/GetAllSettingsController/getAll",
                saveAll: "/api/InstanceSettings/SaveAllSettingsController/saveAll",
                getMyOverview: "/api/InstanceSettings/GetMySettingsOverviewController/get"
              },
              networks: {
                  tools: {
                      findIpAddress: "/api/Networks/Tools/FindIpAddressController/find"
                  },
                  getAll: "/api/Networks/GetHostsNetworksController/get",
                  get: "/api/Networks/GetNetworkController/get",
                  deleteNetwork: "/api/Networks/DeleteNetworkController/delete",
                  createNetwork: "/api/Networks/CreateNetworkController/create",
                  getDashboard: "/api/Networks/GetNetworksDashboardController/get"
              },
              storage: {
                  volumes: {
                      get: "/api/Storage/Volumes/GetStorageVolumeController/get",
                      getOnHost: "/api/Storage/Volumes/GetHostStorageVolumesController/get",
                      create: "/api/Storage/Volumes/CreateStorageVolumeController/create",
                      delete: "/api/Storage/Volumes/DeleteStorageVolumeController/delete",
                  },
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
                      getCommonProfiles: "/api/Profiles/Search/SearchProfiles/getAllCommonProfiles",
                      searchHostProfiles: "/api/Profiles/Search/SearchProfiles/searchHostProfiles"
                  },
                  getProfile: '/api/Profiles/GetProfileController/get',
                  getAllProfiles: '/api/Profiles/GetAllProfilesController/getAllProfiles',
                  delete: '/api/Profiles/DeleteProfileController/delete',
                  rename: '/api/Profiles/RenameProfileController/rename',
                  copy: '/api/Profiles/CopyProfileController/copyProfile',
                  create: '/api/Profiles/CreateProfileController/create',
                  getDashboard: '/api/Profiles/GetProfilesDashboardController/get',
              },
              hosts: {
                  gpu: {
                    getAll: "/api/Hosts/GPU/GetAllController/getAll"
                  },
                  settings: {
                    update: "/api/Hosts/Settings/UpdateSettingsController/update"
                  },
                  search: {
                      search: "/api/Hosts/SearchHosts/search"
                  },
                  instances: {
                      deleteProxyDevice: "/api/Hosts/Instances/DeleteProxyDeviceController/delete",
                      addProxyDevice: "/api/Hosts/Instances/AddProxyDeviceController/add",
                      getAllProxyDevices: "/api/Hosts/Instances/GetAllProxyDevicesController/get",
                      delete: "/api/Hosts/Instances/DeleteInstancesController/delete",
                      stop: "/api/Hosts/Instances/StopInstancesController/stop",
                      start: "/api/Hosts/Instances/StartInstancesController/start",
                      getHostContainers: "/api/Hosts/Instances/GetHostsInstancesController/get"
                  },
                  warnings: {
                      delete: "/api/Hosts/Warnings/DeleteWarningController/delete",
                      acknowledge: "/api/Hosts/Warnings/AckWarningController/ack",
                      getOnHost: "/api/Hosts/Warnings/GetHostWarningsController/getOnHost"
                  },
                  getAllHosts: "/api/Hosts/GetHostsController/getAllHosts",
                  getOverview: "/api/Hosts/GetOverviewController/get",
                  delete: "/api/Hosts/DeleteHostController/delete",
                  getHostOverview: "/api/Hosts/GetHostOverviewController/get"
              },
              images: {
                  aliases: {
                      create: "/api/Images/Aliases/CreateController/create",
                      delete: "/api/Images/Aliases/DeleteController/delete",
                      rename: "/api/Images/Aliases/RenameController/rename",
                      updateDescription: "/api/Images/Aliases/UpdateDescriptionController/update"
                  },
                  search: {
                      searchAllHosts: "/api/Images/Search/SearchController/getAllAvailableImages",
                  },
                  proprties: {
                      getFiltertedList: '/api/Images/GetImagePropertiesController/getFiltertedList',
                      update: '/api/Images/UpdateImagePropertiesController/update',
                      get: '/api/Images/GetImagePropertiesController/getAll'
                  },
                  getLinuxContainersOrgImages: "/api/Images/SearchRemoteImagesController/get",
                  delete: "/api/Images/DeleteImagesController/delete",
                  getAll: "/api/Images/GetImagesController/getAllHostImages",
                  import: "/api/Images/ImportRemoteImagesController/import",
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
                  tokens: {
                      create: '/api/User/Tokens/CreateTokenController/create',
                      delete: '/api/User/Tokens/DeleteTokenController/delete',
                  },
                  dashboard: {
                    graphs: {
                       add: '/api/User/Dashboard/Graphs/AddGraphController/add',
                       delete: '/api/User/Dashboard/Graphs/DeleteGraphController/delete',
                    },
                    create: '/api/User/Dashboard/CreateDashboardController/create',
                    get: '/api/User/Dashboard/GetDashboardController/get',
                    delete: '/api/User/Dashboard/DeleteDashboardController/delete'
                  },
                  getUserOverview:'/api/User/GetUserOverviewController/get',
                  setHostProject: '/api/User/SetHostProjectController/set'
              },
              projects: {
                  users: {
                    getUsersWithAccess: '/api/Projects/Users/GetUsersWithAccessController/get'
                  },
                  search:{
                    getCommonToHosts: '/api/Projects/Search/GetCommonToHostsProjectsController/get',
                  },
                  getOverview: '/api/Projects/GetProjectsOverviewController/get',
                  create: '/api/Projects/CreateProjectController/create',
                  getAllFromHosts: '/api/Projects/GetHostsProjectsController/get',
                  getProjectsOverview: '/api/Projects/GetHostsProjectsOverviewController/get',
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

          $(document).on("click", ".goToInstance", function(){
              currentContainerDetails = $(this).data();
              createDashboardSidebar();
              loadContainerView($(this).data());
          });

          $(document).on("click", ".toProfile", function(){
              let profile = $(this).data("profile");

              if(Object.keys($(this).data()).includes("hostId")){
                  currentContainerDetails = $(this).data();
              }

              loadProfileView(profile, currentContainerDetails.hostId, function(){
                  viewProfile(profile, currentContainerDetails.alias, currentContainerDetails.hostId);
              });
          });
      </script>
  </head>
  <form style="display: none;" method="POST" id="downloadContainerFileForm" action="/api/Instances/Files/GetPathController/get">
      <input value="" name="hostId"/>
      <input value="" name="path"/>
      <input value="" name="container"/>
      <input value="1" type="number" name="download"/>
  </form>
  <body style="overflow: hidden;">
     <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
          <a class="navbar-brand col-lg-2 me-0 px-3" href="#" style="width: 300px;">
              <img src="/assets/lxdMosaic/logo.png" class="me-1 ms-1" style="width: 25px; height: 25px;" alt="">
              <?= $siteTitle ?>
          </a>
          <ul class="nav me-auto mb-2 mb-lg-0" id="mainNav">
              <li class="nav-item active">
                <a class="nav-link overview">
                  <i class="fas fa-tachometer-alt"></i>
                  <span class="hideNavText"> Dashboard </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link viewBackups">
                  <i class="fas fa-save"></i> <span class="hideNavText"> Backups </span> </a>
              </li>
              <li class="nav-item">
                <a class="nav-link viewCloudConfigFiles">
                  <i class="fa fa-cogs"></i> <span class="hideNavText"> Cloud Config </span></a>
              </li>
              <li class="nav-item">
                  <a class="nav-link viewDeployments">
                  <i class="fas fa-rocket"></i> <span class="hideNavText"> Deployments </span></a>
              </li>
              <li class="nav-item">
                  <a class="nav-link viewImages">
                  <i class="fa fa-images"></i> <span class="hideNavText"> Images </span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link viewNetwork">
                  <i class="fas fa-network-wired"></i> <span class="hideNavText"> Networks </span> </a>
              </li>
              <li class="nav-item">
                <a class="nav-link viewProfiles callFunc">
                  <i class="fas fa-users"></i>
                  <span class="hideNavText"> Profiles </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link viewProjects">
                  <i class="fas fa-project-diagram"></i> <span class="hideNavText"> Projects </span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link viewStorage">
                  <i class="fas fa-hdd"></i> <span class="hideNavText"> Storage </span> </a>
              </li>
              <li class="nav-item">
                <a class="nav-link viewSettings">
                  <i class="fas fa-wrench"></i> <span class="hideNavText"> Settings </span> </a>
              </li>
          </ul>
          <ul class="nav ms-auto">
              <li class="nav-item px-3 btn btn-outline-purple pull-right" data-toggle="tooltip" data-bs-placement="bottom" title="Search" id="openSearch">
                    <a> <i class="fas fa-search"></i></a>
              </li>
              <li class="nav-item dropdown">
                  <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                     <i class="fas fa-project-diagram"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-lg-end" aria-labelledby="dropdownMenuButton1" style="min-width: 20vw;">
                      <div class="px-4 py-3" id="navProjectControlHostList">
                      </div>
                  </ul>
              </li>
              <li class="nav-item dropdown">
                  <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                     <i class="fas fa-plus"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-lg-end" aria-labelledby="dropdownMenuButton1">
                      <?php if ($isAdmin === 1) : ?>
                       <li><a id="addNewServer" class="dropdown-item" href="#"><i class="fas fa-server me-2"></i>Add Server</a></li>
                      <?php endif; ?>

                    <li><a id="createContainer" class="dropdown-item" href="#"><i class="fas fa-box me-2"></i>Create Container</a></li>
                    <li><a id="createVm" class="dropdown-item" href="#"><i class="fas fa-vr-cardboard me-2"></i>Create VM</a></li>

                  </ul>
              </li>
              <li class="nav-item dropdown">
                  <button class="btn btn-outline-info dropdown-toggle" type="button" id="operationsDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                     <i class="fas fa-bell"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-lg-end px-3" aria-labelledby="operationsDropdownButton" id="operationsList" style="min-width: 20vw;">
                  </ul>
              </li>
               <a href="/logout" class="nav-item px-3 btn btn-outline-secondary pull-right" data-toggle="tooltip" data-bs-placement="bottom" title="Logout" style="height: 35px;">
                    <i style="line-height: 1.25rem" class="fas fa-sign-out-alt"></i>
                </a>
          </ul>
      </header>
      <div class="container-fluid">
          <div class="row p-0">
              <div class="d-flex flex-column flex-shrink-0 pt-1 text-white bg-dark" style="width: 300px;  padding-right: 0px;  height: 100vh; overflow-y: auto; padding-bottom: 25px;">
                <ul class="flex-column scrollarea" id="sidebar-ul" style="list-style: none; padding-left: 0px;">
                </ul>
              </div>
              <div class="col ps-0 pe-0" style="max-height: 100vh; padding-bottom: 50px; overflow-y: auto;">
                <ol class="breadcrumb ps-3 pt-1 pb-1" id="mainBreadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                <div class="container-fluid">
                  <div id="dashboard" class="animated fadeIn">
                    <div class="row">
                        <div class="col-md-12" id="boxHolder">
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
                                require __DIR__ . "/boxes/searchResult.php";
                            ?>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script type='text/javascript'>

$(".boxSlide").hide();
$("#filterDashProjectAnalyticsProject").val("")
$("#overviewGraphs").html("<h1 class='text-center'><i class='fas fa-cog fa-spin'></i></h1>");

$("#sidebar-ul").on("click", ".nav-item", function(){
    if($(this).hasClass("dropdown")){
        return;
    }

    if(dashboardRefreshInterval != null){
        clearInterval(dashboardRefreshInterval);
    }

    $("#sidebar-ul").find(".text-info").removeClass("text-info");
    $(this).find(".nav-link").addClass("text-info");
});


$(".sidebar-nav").on("click", ".nav-item", function(){
    if(consoleSocket !== undefined && currentTerminalProcessId !== null){
        consoleSocket.close();
        currentTerminalProcessId = null;
    }
});

window.setInterval(function(){

}, 500);


var editor = ace.edit("editor");
  editor.setTheme("ace/theme/monokai");
  editor.getSession().setMode("ace/mode/yaml");

$(function(){
    if(recordActionsEnabled == 0){
        $("#goToEvents").hide();
    }
    Chart.defaults.global.defaultFontColor='white';
    $('[data-toggle="tooltip"]').tooltip({html: true})
    loadDashboard();
    createDashboardSidebar();
    window.setInterval(clearOldOperations, 60 * 1000);
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

function nextLetter(s){
    return s.replace(/([a-zA-Z])[^a-zA-Z]*$/, function(a){
        var c= a.charCodeAt(0);
        switch(c){
            case 90: return 'A';
            case 122: return 'a';
            default: return String.fromCharCode(++c);
        }
    });
}


function createDashboardSidebar()
{
    ajaxRequest(globalUrls.universe.getEntities, {}, (data)=>{
        data = makeToastr(data);

        let hosts = `
            <li class="mt-2 container-overview">
                <a class="" href="#">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>`;

        $.each(data.clusters, function(i, item){
            hosts += `<li data-cluster="${i}" class="c-sidebar-nav-title cluster-title text-success ps-1 pt-2"><u>Cluster ${i}</u></li>`;

            hostsTrs += `<tr><td colspan="999" class="bg-success text-center text-white">Cluster ${i}</td></tr>`

            $.each(item.members, function(_, host){
                let disabled = "";
                let expandBtn = '<button class="btn btn-outline-secondary float-end showServerInstances"><i class="fas fa-caret-left"></i></button>';

                if(!host.hostOnline){
                    disabled = "disabled text-warning text-strikethrough";
                    expandBtn = '';
                }

                hosts += `<li data-hostId="${host.hostId}" data-alias="${host.alias}" class="nav-item containerList dropdown">
                    <a class="nav-link viewServer ${disabled}" href="#">
                        <i class="fas fa-server"></i> ${host.alias}
                        ${expandBtn}
                    </a>
                    <div id="${host.hostId}" class="collapse">
                        <ul class="dropdown-menu dropdown-menu-dark hostInstancesUl">
                        </ul>
                    </div>
                </li>`;
            });
        });

        hosts += `<li class="c-sidebar-nav-title text-success pt-2"><u>Standalone Hosts</u></li>`;

        let currentId = "a";

        $.each(data.standalone.members, function(_, host){
            let disabled = "";
            let expandBtn = `<button class="btn btn-outline-secondary btn-toggle collapsed float-end showServerInstances" ata-bs-toggle="collapse" data-bs-target="#${host.hostId}" aria-expanded="false"><i class="fas fa-caret-left"></i></button>`;

            if(host.hostOnline == false){
                disabled = "disabled text-warning text-strikethrough";
                expandBtn = '';
            }

            hosts += `<li class="mb-2" data-hostId="${host.hostId}" data-alias="${host.alias}">
                <a class="d-inline viewServer ${disabled}" href="#">
                    <i class="fas fa-server me-2"></i>${host.alias}
                </a>
                <button class="btn  btn-outline-secondary btn-sm btn-toggle align-items-center rounded collapsed showServerInstances d-inline float-end me-2" data-bs-toggle="collapse" data-bs-target="#${currentId}" aria-expanded="false">
                    <i class="fas fa-caret-left"></i>
                </button>
                <div class="collapse mt-2 bg-dark text-white" id="${currentId}">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small hostInstancesUl" style="display: inline;">
                    </ul>
                </div>
             </li>`
             currentId = nextLetter(currentId)
        });


        $("#sidebar-ul").empty().append(hosts);
    });
}

$(document).on("click", '.search-panel .dropdown-menu', function(e) {
   e.preventDefault();
   var target = $(e.target)
   let targetData = target.data();
   if(!targetData.hasOwnProperty("search")){
       targetData = target.parent().data();
   }

   let search = "";
   if (targetData.search == "containers"){
       search = "container";
   } else if (targetData.search == "vms"){
       search = "vm";
   }

   $(this).parents("ul").find('.search_concept').html(`<i class="fas fa-${targetData.icon}"></i>`).data("filter", search)

   let ul = target.parents("ul");
   ul.parents("li").find(".hostInstancesUl").css("min-height", "200px");
   let inputSearch = ul.find(".filterHostsInstances").val().toLowerCase();
   ul.find(".view-container").filter(function(){
       $(this).toggle($(this).data("type").toLowerCase().indexOf(search) > -1 && $(this).text().toLowerCase().indexOf(inputSearch) > -1)
   });
});

function addHostContainerList(hostId, hostAlias) {
    ajaxRequest(globalUrls.hosts.instances.getHostContainers, {hostId: hostId}, (data)=>{
        data = makeToastr(data);
        let containers = "";
        if(Object.keys(data).length > 0){

            if(Object.keys(data).length > 5){
                containers += `<li class="">
                    <div class="input-group pe-3 mb-2" style="padding-left: 5px;">
                        <div class="input-group-btn search-panel">
                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <span class="search_concept" data-filter=""><i class="fas fa-filter"></i></span> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark" role="menu">
                              <li class='dropdown-item' data-search="all" data-icon="filter"><a href="#"><i class="fas fa-filter me-2"></i>All</a></li>
                              <li class='dropdown-item' data-search="containers" data-icon="box"><a href="#"><i class="fas fa-box me-2"></i>Containers</a></li>
                              <li class='dropdown-item' data-search="vms" data-icon="vr-cardboard"><a href="#"><i class="fas fa-vr-cardboard me-2"></i>Virtual Machines</a></li>
                            </ul>
                        </div>
                        <input type="text" class="form-control form-control-sm filterHostsInstances" placeholder="Search instances...">
                    </div>
                </li>`
            }

            $.each(data, function(containerName, details){
                let active = "";
                if(currentContainerDetails !== null && currentContainerDetails.hasOwnProperty("container")){
                    if(hostId == currentContainerDetails.hostId && containerName == currentContainerDetails.container){
                        active = "text-info"
                    }
                }

                let typeFa = "box";
                let type = "container";

                if(details.hasOwnProperty("type") && details.type == "virtual-machine"){
                    typeFa = "vr-cardboard";
                    type = "vm";
                }

                let osIconMap = {
                    centos: 'centos',
                    opensuse: 'suse',
                    fedora: 'fedora',
                    ubuntu: 'ubuntu'
                };

                let osIcon = "linux";
                let instanceImage = ""

                if(details.config.hasOwnProperty("image.os") && details.config["image.os"] != null){
                    instanceImage = details.config["image.os"].toLowerCase();
                }

                if(osIconMap.hasOwnProperty(instanceImage)){
                    osIcon = osIconMap[instanceImage];
                }

                containers += `<li class="view-container"
                    data-host-id="${hostId}"
                    data-container="${containerName}"
                    data-alias="${hostAlias}"
                    data-type="${type}">
                  <a class="text-white ${active}" href="#">
                    <i class="nav-icon me-2 ${statusCodeIconMap[details.state.status_code]}"></i>
                    <i class="nav-icon me-2 fas fa-${typeFa}"></i>
                    <i class="nav-icon me-2 fab fa-${osIcon}"></i>
                    ${containerName}
                  </a>
                </li>`;
            });
        }else {
            containers += `<li class="nav-item text-center text-warning">No Instances</li>`;
        }
        $("#sidebar-ul").find("li[data-hostid=" + hostId + "] ul").empty().append(containers).show()
    });
}

$(document).on("keyup", ".filterHostsInstances", function(e){
    let ul = $(this).parents("ul");
    let search = $(this).val().toLowerCase();
    let typeFilter = $(this).parents("ul").find(".search_concept").data("filter");
    ul.parents("li").find(".hostInstancesUl").css("min-height", "200px");
    ul.find(".view-container").filter(function(){
        $(this).toggle($(this).text().toLowerCase().indexOf(search) > -1 && $(this).data("type").toLowerCase().indexOf(typeFilter) > -1)
    });
});

$(document).on("click", ".cluster-title", function(e){
    if(!userDetails.isAdmin){
        return false;
    }
    let x = $(this).data();
    $("#sidebar-ul").find(".text-info").removeClass("text-info");
    $("#sidebar-ul").find(".active").removeClass("active");
    $(this).addClass("text-info");
    loadClusterView(x.cluster);
});

$(document).on("click", ".showServerInstances", function(e){
    e.preventDefault();

    let parentLi = $(this).parents("li");

    if(parentLi.hasClass("open")){
        parentLi.find("ul").empty();
        parentLi.removeClass("open");
        parentLi.find(".hostInstancesUl").css("min-height", "0px");
        $(this).html('<i class="fas fa-caret-left"></i>')
        return false;
    }else{
        $(this).html('<i class="fas fa-caret-down"></i>')
        parentLi.addClass("open");
    }

    let hostId = parentLi.data("hostid");
    let hostAlias = parentLi.data("alias");

    currentContainerDetails = null;

    addHostContainerList(hostId, hostAlias);

    return false;
});

$(document).on("click", ".viewServer", function(){
    let parentLi = $(this).parents("li");

    let hostId = parentLi.data("hostid");
    let hostAlias = parentLi.data("alias");



    currentContainerDetails = null;
    loadServerView(hostId);
});

function loadDashboard(){
    if(dashboardRefreshInterval != null){
        clearInterval(dashboardRefreshInterval);
    }

    $("#userDashboardGraphs").empty();
    $(".boxSlide, #userDashboard, #projectAnalyticsDashboard").hide();
    $("#overviewBox, #generalDashboard").show();
    setBreadcrumb("Dashboard", "active overview");
    changeActiveNav(".overview")

    ajaxRequest(globalUrls.dashboard.get, {}, (data)=>{
        data = makeToastr(data);

        let lis = `<li class="nav-item">
          <a class="nav-link active viewDashboard" id="generalDashboardLink" href="#">General</a>

        </li>
        <li class="nav-item">
            <a class="nav-link viewDashboard" id="projectAnalyticsDashboardLink" href="#">Project Analytics</a>
        </li>`;

        $.each(data.userDashboards, (_, dashboard)=>{
            lis += `<li class="nav-item">
              <a class="nav-link viewDashboard" id="${dashboard.id}" href="#">${dashboard.name}</a>
            </li>`;
        });

        $("#userDashboardsList").empty().append(lis);

        let projectsDropdown = "";

        $.each(data.clustersAndHosts.clusters, function(i, item){

            let cTitleClass = userDetails.isAdmin ? "cluster-title" : "cluster-title-not-admin";

            projectsDropdown += `<b>Cluster ${i}</b>`

            $.each(item.members, function(_, host){
                let disabled = "";
                if(!host.hostOnline){
                    disabled = "disabled text-warning text-strikethrough";
                }

                let projects = "<div class='text-center text-info'><i class='fas fa-info-circle me-2'></i>Not Supported</div>";

                if(host.resources.hasOwnProperty("extensions") && host.resources.extensions.supportsProjects){
                    projects = "<select class='form-control changeHostProject'>";
                    $.each(host.projects, function(o, project){
                        let selected = project == host.currentProject ? "selected" : "";
                            projects += `<option data-alias="${host.alias}" data-host='${data.hostId}'
                                value='${project}' ${selected}>
                                ${project}</option>`;
                    });
                    projects += "</select>";
                }

                if(host.hostOnline == true){
                    projectsDropdown += `<div><b>${host.alias}</b>${projects}</div>`
                }
            });
        });

        projectsDropdown += `<b class="text-success">Standalone Hosts</b>`

        $.each(data.clustersAndHosts.standalone.members, function(_, host){
            let disabled = "";

            if(host.hostOnline == false){
                disabled = "disabled text-warning text-strikethrough";
            }

            let projects = "<div class='text-center text-info'><i class='fas fa-info-circle me-2'></i>Not Supported</div>";

            if(host.resources.hasOwnProperty("extensions") && host.resources.extensions.supportsProjects){
                projects = "<select class='form-control changeHostProject'>";
                $.each(host.projects, function(o, project){
                    let selected = project == host.currentProject ? "selected" : "";
                        projects += `<option data-alias="${host.alias}" data-host='${host.hostId}'
                            value='${project}' ${selected}>
                            ${project}</option>`;
                });
                projects += "</select>";
            }

            if(host.hostOnline == true){
                projectsDropdown += `<div><i class="fas fa-server me-2"></i><b>${host.alias}</b>${projects}</div>`;
                openHostOperationSocket(host.hostId, host.currentProject);
            }
        });
        $("#navProjectControlHostList").empty().append(projectsDropdown);


        let displayItems = {
            "Instances": {
                formatBytes: false,
                icon: 'fas fa-box'
            },
            "Disk": {
                formatBytes: true,
                icon: 'fas fa-hdd'
            },
            "Memory": {
                formatBytes: true,
                icon: 'fas fa-memory'
            },
            "Processes": {
                formatBytes: false,
                icon: 'fas fa-microchip'
            }
        }

        $("#overviewGraphs").empty();
        $("#totalsGraphs").empty();

        $.each(data.projectGraphData.projectAnalytics, (alias, projects)=>{
            $.each(projects, (project, analytics)=>{
                let y = $(`
                <div class="row projectRow" data-project="${project}">
                    <div class="col-md-12 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                        <h4 class="mb-2"><i class="fas fa-server text-info me-2"></i>${alias}</h4>
                        <h4><i class="fas fa-project-diagram text-info me-2"></i>${project}</h4>
                    </div>
                    <div class="row graphs">
                    </div>
                </div>
                `);

                $.each(displayItems, (title, config)=>{
                    let labels = [];
                    let values = [];
                    let limits = [];

                    let cId = project + "-" + title.toLowerCase();

                    $.each(data.projectGraphData.projectAnalytics[alias][project][title], (_, entry)=>{
                        labels.push(moment.utc(entry.created).local().format("HH:mm"))
                        values.push(entry.value)
                        limits.push(entry.limit)
                    });

                    var totalUsage = values.reduce(function(a, b){
                        return parseInt(a) + parseInt(b);
                    }, 0);

                    let canvas = `<canvas height="200" width="200" id="${cId}"></canvas>`;

                    if(totalUsage == 0){
                        canvas = '<div style="min-height: 200;" class="text-center "><i class="fas fa-info-circle  text-primary me-2"></i>No Usage</div>'
                    }


                    let x = $(`<div class='col-md-3'>
                          <div class="card bg-dark text-white">
                              <div class="card-header">
                                  <i class="${config.icon} me-2"></i>${title}
                              </div>
                              <div class="card-body">
                                ${canvas}
                              </div>
                          </div>
                      </div>`);

                    if(totalUsage > 0){
                        let graphDataSets = [
                            {
                                label: "total",
                                borderColor: 'rgba(46, 204, 113, 1)',
                                pointBackgroundColor: "rgba(46, 204, 113, 1)",
                                pointBorderColor: "rgba(46, 204, 113, 1)",
                                data: values
                            }
                        ];

                        let filtLimits = limits.filter(onlyUnique)
                        //
                        if(filtLimits.length !== 1 || filtLimits[0] !== null){
                            graphDataSets.push({
                                label: "limit",
                                borderColor: '#09F',
                                pointBackgroundColor: "#09F",
                                pointBorderColor: "#09F",
                                data: limits
                            })
                        }

                        let options = {responsive: true};

                        if (config.formatBytes) {
                              options.scales = scalesBytesCallbacks;
                              options.tooltips = toolTipsBytesCallbacks
                        }else{
                            options.scales = {
                                yAxes: [{
                                  ticks: {
                                    precision: 0,
                                    beginAtZero: true
                                  }
                                }]
                            }
                        }

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

                $("#overviewGraphs").append(y)
            });
        });

        let y = $(`<div class="row mb-2" ></div>`)
        $.each(displayItems, (title, config)=>{


            let labels = [];
            let values = [];
            let limits = [];

            let cId = title.toLowerCase();

            $.each(data.projectGraphData.totals[title], (created, value)=>{
                labels.push(moment.utc(created).local().format("HH:mm"))
                values.push(value)
            });

            var totalUsage = values.reduce(function(a, b){
                return parseInt(a) + parseInt(b);
            }, 0);

            let canvas = `<canvas height="200" width="200" id="${cId}"></canvas>`;

            if(totalUsage == 0){
                canvas = '<div style="min-height: 200;" class="text-center "><i class="fas fa-info-circle  text-primary me-2"></i>No Usage</div>'
            }


            let x = $(`<div class='col-md-3'>
                  <div class="card bg-dark text-white">
                      <div class="card-body">
                        <h4 class="mb-3 text-center"><i class="${config.icon} me-2"></i>${title}</h4>
                        ${canvas}
                      </div>
                  </div>
              </div>`);

            if(totalUsage > 0){
                let graphDataSets = [
                    {
                        label: "total",
                        borderColor: 'rgba(46, 204, 113, 1)',
                        pointBackgroundColor: "rgba(46, 204, 113, 1)",
                        pointBorderColor: "rgba(46, 204, 113, 1)",
                        data: values
                    }
                ];

                let options = {responsive: true};

                if (config.formatBytes) {
                      options.scales = scalesBytesCallbacks;
                      options.tooltips = toolTipsBytesCallbacks
                }else{
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
                  ticks: {
                        callback: function(){
                            console.log("me");
                            return '';
                        }
                      }
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
        $("#totalsGraphs").append(y)
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
    $("#sidebar-ul").find(".active").removeClass("active");
    $(this).addClass("active")

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

$(document).on("click", "#openSearch", function(){
    $.confirm({
        title: `Search`,
        content: `
            <div class="mb-2">
                <label>IP Address IPV4/IPV6</label>
                <input class="form-control" name="ip" />
            </div>
        `,
        buttons: {
            cancel: {
                btnClass: "btn btn-secondary",
                text: "cancel"
            },
            search: {
                btnClass: "btn btn-success",
                text: "Search",
                action: function(){
                    let x = {
                        ip: this.$content.find("input[name=ip]").val()
                    }

                    ajaxRequest(globalUrls.networks.tools.findIpAddress, x, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            return false;
                        }
                        if(data.result == false){
                            makeToastr({state: "error", message: "Couldn't find instance"})
                            return false;
                        }
                        currentContainerDetails = data.result;
                        loadContainerView(data.result);
                    });
                }
            }
        }
    });
});

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
        selected.parents(".dropdown-menu").removeClass("show");
        makeToastr(data);
        addHostContainerList(x.hostId, selected.data("alias"));
        openHostOperationSocket(x.hostId, selected.val());
    })

});

$(document).on("click", ".overview, .container-overview", function(){

    $(".sidebar-fixed").addClass("sidebar-lg-show");
    currentContainerDetails = null;
    createDashboardSidebar();
    loadDashboard();
});

$(document).on("click", ".viewProfiles, .profile-overview", function(){
    setBreadcrumb("Profiles", "viewProfiles active");


    $(".sidebar-fixed").addClass("sidebar-lg-show");
    changeActiveNav(".viewProfiles");

    $(".boxSlide, #profileDetails").hide();
    $("#profileOverview, #profileBox").show();

    if($(this).hasClass("callFunc")){
        loadProfileView();
    }
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

$(document).on("click", ".openProjectAccess", function(){
    projectAccessObj = $(this).data();
    $("#modal-projects-access").modal("show");
});
</script>
<?php
    require_once __DIR__ . "/modals/hosts/addHosts.php";
    require_once __DIR__ . "/modals/images/import.php";
    require_once __DIR__ . "/modals/projects/projectAccess.php";
?>
</html>
