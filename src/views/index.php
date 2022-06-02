<?php
$haveServers = $this->container->make("dhope0000\LXDClient\Model\Hosts\HostList"); /** @phpstan-ignore-line */

$userSession = $this->container->make("dhope0000\LXDClient\Tools\User\UserSession"); /** @phpstan-ignore-line */
$validatePermissions = $this->container->make("dhope0000\LXDClient\Tools\User\ValidatePermissions"); /** @phpstan-ignore-line */
$getSetting = $this->container->make("dhope0000\LXDClient\Model\InstanceSettings\GetSetting"); /** @phpstan-ignore-line */
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
      <link rel="manifest" href="/assets/lxdMosaic/favicons/manifest.json">
      <meta name="msapplication-TileColor" content="#ffffff">
      <meta name="msapplication-TileImage" content="/assets/lxdMosaic/favicons/ms-icon-144x144.png">
      <meta name="theme-color" content="#ffffff">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <script src="/assets/dist/external.dist.js" type="text/javascript" charset="utf-8"></script>
      <script src="/assets/lxdMosaic/lxdDevicesProperties.js" type="text/javascript" charset="utf-8"></script>

      <!-- <link rel="stylesheet" href="/assets/xterm/xterm.css" /> -->

      <!-- Main styles for this application-->
      <link href="/assets/dist/external.dist.css" rel="stylesheet">

      <link rel="stylesheet" href="/assets/lxdMosaic/styles.css">

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
                      schedule: "/api/Instances/Snapshot/ScheduleSnapshotsController/schedule",
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
                  projects: {
                    getAll: '/api/Deployments/Projects/GetDeploymentProjectsController/get',
                    set: '/api/Deployments/Projects/SetDeploymentProjectsController/set'
                  },
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
                  getDetails: '/api/CloudConfig/GetDetailsController/get'
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

          $(document).on("click", ".toggleDropdown", function(){
             if($(this).find(".fa-caret-down").length){
                 $(this).find(".fa-caret-down").removeClass("fa-caret-down").addClass("fa-caret-left")
             }else{
                 $(this).find(".fa-caret-left").removeClass("fa-caret-left").addClass("fa-caret-down")
             }
          });

          $(document).on('show.bs.modal', '.modal', function (event) {
              var zIndex = 1055 + (10 * $('.modal:visible').length);
              $(this).css('z-index', zIndex);
              setTimeout(function() {
                  $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
              }, 0);
          });

          $(document).on("click", "#createVm", function(){
              $("#modal-vms-create").modal("show");
          });

          $(document).on("click", "#createContainer", function(){
              $("#modal-container-create").modal("show");
          });

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
                                  router.navigate(`/instance/${hostIdOrAliasForUrl(data.result.alias, data.result.hostId)}/${data.result.container}`);
                              });
                          }
                      }
                  }
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
          <a class="navbar-brand col-lg-2 me-0 px-2" href="/" style="width: 20vw;" data-navigo>
              <img src="/assets/lxdMosaic/logo.png" class="me-1 ms-1" style="width: 25px; height: 25px;" alt="">
              <?= $siteTitle ?>
          </a>
          <ul class="nav me-auto mb-2 mb-lg-0" id="mainNav">
              <li class="nav-item active">
                <a class="nav-link overview" href="/" data-navigo>
                  <i class="fas fa-tachometer-alt"></i>
                  <span class="hideNavText"> Dashboard </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link viewBackups" href="/backups" data-navigo>
                  <i class="fas fa-save"></i> <span class="hideNavText"> Backups </span> </a>
              </li>
              <li class="nav-item">
                <a class="nav-link viewCloudConfigFiles" href="/cloudConfig" data-navigo>
                  <i class="fa fa-cogs"></i> <span class="hideNavText"> Cloud Config </span></a>
              </li>
              <li class="nav-item">
                  <a class="nav-link viewDeployments" href="/deployments" data-navigo>
                  <i class="fas fa-rocket"></i> <span class="hideNavText"> Deployments </span></a>
              </li>
              <li class="nav-item">
                  <a class="nav-link viewImages" href="/images" data-navigo>
                  <i class="fa fa-images"></i> <span class="hideNavText"> Images </span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link viewNetwork" href="/networks" data-navigo>
                  <i class="fas fa-network-wired"></i> <span class="hideNavText"> Networks </span> </a>
              </li>
              <li class="nav-item">
                <a class="nav-link viewProfiles callFunc" href="/profiles" data-navigo>
                  <i class="fas fa-users"></i>
                  <span class="hideNavText"> Profiles </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link viewProjects" href="/projects" data-navigo>
                  <i class="fas fa-project-diagram"></i> <span class="hideNavText"> Projects </span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link viewStorage" href="/storage" data-navigo>
                  <i class="fas fa-hdd"></i> <span class="hideNavText"> Storage </span> </a>
              </li>
          </ul>
          <div class="btn-group ms-auto" id="buttonsNavbar">
              <button class="btn btn-outline-purple pt-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Search" id="openSearch">
                  <i class="fas fa-search"></i>
              </button>
              <div class="btn-group">
                  <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" >
                     <i class="fas fa-project-diagram"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-lg-end" aria-labelledby="dropdownMenuButton1" style="min-width: 20vw;">
                      <div class="px-4 py-3" id="navProjectControlHostList">
                      </div>
                  </ul>
              </div>
              <div class="btn-group dropdown">
                  <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                     <i class="fas fa-plus"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-lg-end" aria-labelledby="dropdownMenuButton1">
                    <li><a id="createContainer" class="dropdown-item"><i class="fas fa-box me-2"></i>Create Container</a></li>
                    <li><a id="createVm" class="dropdown-item"><i class="fas fa-vr-cardboard me-2"></i>Create VM</a></li>

                  </ul>
              </div>
              <div class="btn-group dropdown">
                  <button class="btn btn-outline-info dropdown-toggle" type="button" id="operationsDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                     <i class="fas fa-bell"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-lg-end px-3" aria-labelledby="operationsDropdownButton" id="operationsList" style="min-width: 20vw;">
                      <li id="noOps"><div class="dropdown-item" href="#"><i class="fas fa-info-circle text-info me-2"></i>No Operations In Progress</div></li>
                  </ul>
              </div>
              <?php if ($isAdmin === 1) : ?>
                  <button class="btn btn-outline-warning viewSettings" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Admin Settings" href="/admin" data-navigo>
                        <i class="fas fa-cogs"></i>
                  </button>
              <?php endif; ?>
              <div class="btn-group dropdown">
                  <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="myAccountBtn" data-bs-toggle="dropdown" aria-expanded="false">
                     <i class="fas fa-user"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-lg-end" aria-labelledby="myAccountBtn">
                      <li><a class="dropdown-item " href="/mySettings" data-navigo>My Settings</a></li>
                      <li><a class="dropdown-item" href="/logout">Logout</a></li>
                  </ul>
              </div>

          </div>
      </header>
      <div class="container-fluid" id="content">
          <div class="row p-0">
              <div class="d-flex flex-column flex-shrink-0 pt-1 text-white bg-dark" style="width: 20vw;  padding-right: 0px;  height: 100vh; overflow-y: auto; padding-bottom: 25px;">
                <ul class="flex-column scrollarea" id="sidebar-ul" style="list-style: none; padding-left: 0px;">
                </ul>
              </div>
              <div class="col ps-0 pe-0" style="max-height: 100vh; padding-bottom: 30px; overflow-y: auto;">
                <ol style="min-height: 30px;" class="breadcrumb ps-3 pt-1 pb-1" id="mainBreadcrumb">

                </ol>
                <div class="container-fluid">
                  <div id="dashboard" class="animated fadeIn">
                    <div class="row">
                        <div class="col-md-12" id="boxHolder">
                            <?php
                                require __DIR__ . "/boxes/dashboard.php";
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
                                require __DIR__ . "/boxes/mySettings.php";
                            ?>
                            <div class="boxSlide" id="notFound">
                                <h4 class="text-center">Not Found</h4>
                            </div>
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

const router = new Navigo('/');

var editor = ace.edit("editor");
  editor.setTheme("ace/theme/monokai");
  editor.getSession().setMode("ace/mode/yaml");

function makeProjectDropDown(member){
    let projects = "<select class='form-select changeHostProject'>";
    $.each(member.projects, function(o, project){
       let selected = project == member.currentProject ? "selected" : "";
           projects += `<option data-alias="${member.alias}" data-host='${member.hostId}'
               value='${project}' ${selected}>
               ${project}</option>`;
    });
    projects += "</select>";
    return projects;
}

var hostsAliasesLookupTable = {};
var hostsIdsLookupTable = {};

$(function(){

    $('[data-bs-toggle="tooltip"]').tooltip({html: true})
    openEventSocket()

    router.hooks({
        before(done, match){
            if(Object.keys(hostsAliasesLookupTable).length == 0){
                ajaxRequest(globalUrls.universe.getEntities, {entity: "projects"}, function(data){
                    data = $.parseJSON(data)
                    let providedHostId = match.data !== null && match.data.hasOwnProperty("hostId") ? match.data.hostId : null;
                    let projectsDropdown = "";

                    $.each(data.clusters, function(clusterIndex, cluster){
                        projectsDropdown += `<b class="text-success">Cluster ${clusterIndex}</b>`
                        $.each(cluster.members, (_, member)=>{
                            hostsAliasesLookupTable[member.hostId] = member.alias
                            hostsIdsLookupTable[member.alias] = member.hostId
                            if(member.alias == providedHostId){
                                match.data.hostId = member.hostId
                            }
                            let projects = makeProjectDropDown(member)
                            if(member.hostOnline == true){
                                projectsDropdown += `<div><b>${member.alias}</b>${projects}</div>`
                            }
                        });
                    });

                    projectsDropdown += `<b class="text-success">Standalone Hosts</b>`
                    $.each(data.standalone.members, function(_, member){
                        hostsAliasesLookupTable[member.hostId] = member.alias
                        hostsIdsLookupTable[member.alias] = member.hostId
                        if(member.alias == providedHostId){
                            match.data.hostId = member.hostId
                        }
                        let projects = makeProjectDropDown(member)
                        if(member.hostOnline == true){
                            projectsDropdown += `<div><i class="fas fa-server me-2"></i><b>${member.alias}</b>${projects}</div>`;
                            openHostOperationSocket(member.hostId, member.currentProject)
                        }
                    });

                    $("#navProjectControlHostList").empty().append(projectsDropdown);

                    if( match.data !== null && match.data.hasOwnProperty("hostId") && !$.isNumeric(match.data.hostId)){
                         router.navigate("/404")
                         done(false)
                    }else{
                        done()
                    }
                });
            } else if(match.data !== null && match.data.hasOwnProperty("hostId")){
                if(!$.isNumeric(match.data.hostId)){
                    let lookupId = hostsIdsLookupTable[match.data.hostId];
                    if(!$.isNumeric(lookupId)){
                        router.navigate("/404")
                        done(false)
                    }else{
                        match.data.hostId = hostsIdsLookupTable[match.data.hostId]
                        done()
                    }
                }else{
                    done()
                }
            }else{
                done()
            }

        }
    });

    router.on('/', loadDashboard);
    router.on('/host/:hostId/overview', loadHostOverview);

    router.on('/instance/:hostId/:instance', loadContainerViewReq);
    router.on("/backups", loadBackupsView);
    router.on("/cluster/:clusterId", loadClusterView);
    router.on("/cloudConfig", loadCloudConfigOverview);
    router.on("/cloudConfig/:id", loadCloudConfigView);
    router.on("/deployments", loadDeploymentsView);
    router.on("/deployments/:deploymentId", loadDeploymentViewReq);
    router.on("/images", viewImages);
    router.on("/images/:hostId/:fingerprint", viewImage);
    router.on("/networks", loadNetworksView);
    router.on("/networks/:hostId/:network", viewNetwork);
    router.on("/profiles", loadProfileView);
    router.on("/profiles/:hostId/:profile", viewProfileReq);
    router.on("/projects", loadProjectView);
    router.on("/projects/:hostId/:project", viewProjectReq);
    router.on("/storage", loadStorageView);
    router.on("/storage/:hostId/:pool", loadStoragePoolReq);
    router.on("/storage/:hostId/:pool/:volume", routeViewPoolVolume);
    router.on("/mySettings", loadMySettings);
    router.on("/admin", loadSettingsView);
    router.on("/admin/settings", loadSettingsView);
    router.on("/admin/hosts", loadInstancesHostsView);
    router.on("/admin/instanceTypes", loadInstanceTypes);
    router.on("/admin/users", loadUsers);
    router.on("/admin/userAccessControl", loadProjectAccesOverview);
    router.on("/admin/history", loadRecordedActions);
    router.on("/admin/retiredData", loadRetiredData);

    router.on("*", function(){
        $(".boxSlide").hide()
        $("#notFound").show()
    });

    router.resolve();

    Chart.defaults.global.defaultFontColor='white';
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

$(document).on("click", ".openProjectAccess", function(){
    projectAccessObj = $(this).data();
    $("#modal-projects-access").modal("show");
});
</script>
<?php
    require_once __DIR__ . "/modals/images/import.php";
    require_once __DIR__ . "/modals/projects/projectAccess.php";
    require_once __DIR__ . "/modals/helpers/newDeviceObj.php";
    require_once __DIR__ . "/modals/helpers/editDeviceObj.php";

?>
</html>
