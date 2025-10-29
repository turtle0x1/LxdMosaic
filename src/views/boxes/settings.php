<div id="settingsBox" class="boxSlide">
<div id="instanceSettingsBox" class="settingsBox">
    <div class="row">
        <div class="col-md-6">
            <h4>LXDMosaic Settings</h4>
        </div>
        <div class="col-md-6">
            <span class="badge bg-primary float-end" id="newVersion">
            </span>

        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
              <div class="card bg-dark text-white">
                  <div class="card-header d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center" role="tab" >
                      <h5>
                        <a class="text-white" data-bs-toggle="collapse" data-parent="#accordion" href="#currentSettingsTable" aria-expanded="true" aria-controls="currentSettingsTable">
                          General Settings
                        </a>
                        </h5>
                        <div class="btn-toolbar float-end">
                            <div class="btn-group me-2">
                                <a target="_blank" class="btn btn-primary" href="https://lxdmosaic.readthedocs.io/en/latest/LXDMosaic_Settings/">
                                    <i class="fas fa-book-open"></i>
                                </a>
                                <button class="btn btn-success" id="saveSettings">
                                    <i class="fas fa-save"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="currentSettingsTable" class="collapse in show" role="tabpanel" >
                      <div class="card-body bg-dark">
                        <table class="table table-dark table-bordered" id="settingListTable">
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
        </div>
        <div class="col-md-6">
          <div class="card bg-dark text-white">
              <div class="card-header d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center" role="tab" >
                  <h5>
                    <a class="text-white" data-bs-toggle="collapse" data-parent="#accordion" href="#currentSettingsTable" aria-expanded="true" aria-controls="currentSettingsTable">
                      LDAP Settings
                    </a>
                    </h5>
                    <div class="btn-toolbar float-end">
                        <div class="btn-group me-2">
                            <button class="btn btn-success" id="saveLdapSettings">
                                <i class="fas fa-save"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="" class="collapse in show" role="tabpanel" >
                  <div class="card-body bg-dark">
                    <div class="text-center mb-2">
                        <small class="font-italic"><i class="fas fa-info-circle text-info me-2"></i>Users sync'd on the hour every hour!</small>
                    </div>
                    <div class="text-center mb-2">
                        <small class="font-italic"><i class="fas fa-info-circle text-warning me-2"></i>Setting LDAP Server to empty value will disable LDAP syncing &amp; login!</small>
                    </div>
                    <table class="table table-dark table-bordered" id="ldapSettingListTable">
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
        </div>
    </div>
</div>
<div id="recordedActionsBox" class="settingsBox">
    <div class="card bg-dark text-white" id="recordedActionsCard">
      <div class="card-header bg-dark" role="tab" >
        <h5>
          <a class="text-white" data-bs-toggle="collapse" data-parent="#accordion" href="#lastRecordedActions" aria-expanded="true" aria-controls="lastRecordedActions">
            Last <span id="actionCount"></span> Recorded Actions
          </a>
          <button class="btn btn-primary float-end" id="loadMoreRecordedActions">
              <i class="fas fa-search-plus"></i>
          </button>
        </h5>
      </div>
      <div id="lastRecordedActions" class="collapse in show" role="tabpanel">
        <div class="card-body table-responsive bg-dark">
          <table class="table table-dark table-bordered" id="recordedActionsTable">
              <thead>
                  <tr>
                      <th>User</th>
                      <th>Date</th>
                      <th>Controller</th>
                      <th>Params</th>
                  </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
        </div>
      </div>
    </div>
</div>
<div id="instanceHostsBox" class="settingsBox">
    <div class="card bg-dark text-white" id="recordedActionsCard">
      <div class="card-header bg-dark" role="tab" >
        <h5>Hosts
            <button class="btn btn-outline-primary float-end" id="addHostBtn">
                <i class="fas fa-plus"></i>
            </button>
        </h5>
      </div>
      <div id="allHostsList" class="collapse in show" role="tabpanel">
        <div class="card-body table-responsive bg-dark">
          <table class="table table-dark table-bordered" id="hostListTable">
              <thead>
                  <tr>
                      <th>Alias</th>
                      <th>IP</th>
                      <th>Online</th>
                      <th>Certificate Expires</th>
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
        </div>
      </div>
    </div>
</div>
<div id="usersList" class="settingsBox">
    <div class="row">
        <div class="col-md-5">
            <div class="card bg-dark text-white" id="usersCard" style="max-height: 90vh; overflow: hidden">
                <div class="card-header" role="tab">
                    <h5>
                        Users
                        <button class="btn btn-success float-end" id="addUser">
                            <i class="fas fa-plus"></i>
                        </button>
                    </h5>
                </div>
                <div class="card-body" style="max-height: 90vh; overflow-y: scroll">
                    <div class="input-group mb-3">
                        <div class="input-group-text">
                            <i class="fas fa-filter"></i>
                        </div>
                        <input type="text" class="form-control" placeholder="Filter Users" id="filterUsersTable">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="checkbox" value="" id="showDeletedUserFilter">
                            <label for="showDeletedUserFilter" class="form-check-label ms-2">Show Deleted?</label>
                        </div>
                    </div>
                    <table class="table table-dark table-bordered" id="usersTable">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Added</th>
                                <th>Admin</th>
                                <th>Disabled</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div id="userDetails" class="settingsBox">
            </div>
        </div>
    </div>
</div>
<div id="retiredData" class="settingsBox">
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-dark text-white" id="usersCard">
              <div class="card-header" role="tab" >
                <h5>
                  <a class="text-white">
                      <i class="fas fa-chart-line me-2"></i>Fleet Analytics
                  </a>
                </h5>
              </div>
              <div class="card-body">
                  <p>
                      The dashboard graphs were driven by data aggregated about all your hosts,
                      this data wasn't stored on a per host / project basis.
                  </p>
                  <p>
                      This made data like "Active Containers" wrong,
                      we have since added a new way of gathering data by host & project.
                  </p>
                  <p>
                      Because this has been around for so long you may want to
                      keep a copy before it is deleted in the next release!
                  </p>
                  <p class="font-italic">Sample Data</p>
                  <table class="table table-dark table-bordered table-sm" id="retiredData">
                      <thead>
                          <tr>
                              <th>Date</th>
                              <th>Total Memory Usage (Int)</th>
                              <th>Active Containers (Int)</th>
                              <th>Total Storage Usage (Int)</th>
                              <th>Total Storage Available (Int)</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td>2021-01-17 16:00:00</td>
                              <td>123</td>
                              <td>123</td>
                              <td>123</td>
                              <td>123</td>
                          </tr>
                          <tr>
                              <td>2021-01-17 16:05:00</td>
                              <td>123</td>
                              <td>123</td>
                              <td>123</td>
                              <td>123</td>
                          </tr>
                          <tr>
                              <td>2021-01-17 16:10:00</td>
                              <td>123</td>
                              <td>123</td>
                              <td>123</td>
                              <td>123</td>
                          </tr>
                      </tbody>
                  </table>
                </div>
                <div class="card-footer bg-dark">
                    <button class="btn btn-primary float-end" id="downloadOldFleetAnalytics">
                        <i class="fas fa-download me-2"></i>Download CSV
                    </button>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div id="userProjectOverview" class="settingsBox">
        <div class="row">
            <div class="col-md-12" id="allProjectsOverview">
            </div>
        </div>
    </div>
    <div id="instanceTypesOverview" class="settingsBox">
        <div class="row">
            <div class="col-md-2">
                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h4>Providers
                            <button class="btn btn-outline-primary float-end" id="addProvider"><i class="fas fa-plus"></i></button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group" id="providersList">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-10" id="instanceTypesOverviewProviderDetailsSplash">
                <h4 class="text-center"><i class="fas fa-info-circle text-info me-2"></i>Choose a provider</h4>
            </div>
            <div class="col-md-10" id="instanceTypesOverviewProviderDetails">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <h4 class="d-inline" id="providerName"></h4>
                        <button class="btn btn-danger float-end d-inline" id="deleteProvider">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-primary float-end d-inline" id="addInstanceType">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-dark text-white">
                            <div class="card-body">
                                <table id="providerTypesTable" class="table table-bordered table-dark">
                                    <thead>
                                        <tr>
                                            <td>Name</td>
                                            <td>CPU</td>
                                            <td>Memory</td>
                                            <td>Delete</td>
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
    <?php require_once __DIR__ . "/boxComponents/settings/softwareAssets.html" ?>
    <?php require_once __DIR__ . "/boxComponents/settings/timers.html" ?>
    <?php require_once __DIR__ . "/boxComponents/settings/imageServers.html" ?>
</div>

<script>

// Bad this is a global but saves 2 extra api's
var allInstanceTypes = {};

var currentProvider;

var adminSettingUrls = {
    settings: '/admin/settings',
    hosts: '/admin/hosts',
    imageServers: '/admin/imageServers',
    instanceTypes: '/admin/instanceTypes',
    users: '/admin/users',
    userAccessControl: '/admin/userAccessControl',
    history: '/admin/history',
    retiredData: '/admin/retiredData',
    softwareSnapshots: '/admin/softwareAssets',
    timersSnapshots: '/admin/timers',
};

function putAdminSidebar(selected) {
    let sidebar = '';
    if(!userDetails.isAdmin){
        $("#saveSettings, #saveLdapSettings, #addUser, #recordedActionsCard, #usersCard").remove();
    }else{
        sidebar += `
        <li class="c-sidebar-nav-title text-success pt-2"><u>LXDMosaic</u></li>
        <li class="nav-item mt-2 instance-settings">
            <a class="nav-link p-0 ${selected === `${adminSettingUrls.settings}` ? "active" : null }" href="${adminSettingUrls.settings}" data-navigo>
                <i class="fas fa-sliders-h me-2"></i>Settings
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0 ${selected === `${adminSettingUrls.hosts}` ? "active" : null } " href="${adminSettingUrls.hosts}" data-navigo>
                <i class="fas fa-server me-2"></i>Hosts
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0 ${selected === `${adminSettingUrls.instanceTypes}` ? "active" : null }" href="${adminSettingUrls.instanceTypes}" data-navigo>
                <i class="fas fa-cloud me-2"></i>Instance Types
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0 ${selected === `${adminSettingUrls.imageServers}` ? "active" : null }" href="${adminSettingUrls.imageServers}" data-navigo>
                <i class="fas fa-images me-2"></i>Image Servers
            </a>
        </li>
        <li class="c-sidebar-nav-title text-success pt-2"><u>Users</u></li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0  ${selected === `${adminSettingUrls.users}` ? "active" : null }" href="${adminSettingUrls.users}" data-navigo>
                <i class="fas fa-users-cog me-2"></i>All
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0  ${selected === `${adminSettingUrls.userAccessControl}` ? "active" : null }" href="${adminSettingUrls.userAccessControl}" data-navigo>
                <i class="fas fa-users-cog me-2"></i>Project Access
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0  ${selected === `${adminSettingUrls.history}` ? "active" : null }" href="${adminSettingUrls.history}" data-navigo>
                <i class="fas fa-history me-2"></i>Audit Log
            </a>
        </li>
        <li class="c-sidebar-nav-title text-success pt-2"><u>Data</u></li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0  ${selected === adminSettingUrls.softwareSnapshots ? "active" : null }" href="${adminSettingUrls.softwareSnapshots}" data-navigo>
                <i class="fas fa-list me-2"></i>Software Snapshots
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0  ${selected === adminSettingUrls.timersSnapshots ? "active" : null }" href="${adminSettingUrls.timersSnapshots}" data-navigo>
                <i class="fas fa-hourglass-half me-2"></i>Timers Snapshots
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0  ${selected === `${adminSettingUrls.retiredData}` ? "active" : null }" href="${adminSettingUrls.retiredData}" data-navigo>
                <i class="fas fa-eraser me-2"></i>Retired Data
            </a>
        </li>
        `
    }

    $("#sidebar-ul").empty().append(sidebar);
    router.updatePageLinks()
}

function loadSettingsView()
{
    $(".boxSlide").hide();
    $(".settingsBox").hide();
    setBreadcrumb("Admin Settings", "active", "/admin");
    changeActiveNav(null)
    $(".viewSettings").addClass("active");
    $("#settingsBox").show();
    putAdminSidebar(adminSettingUrls.settings);
    changeActiveNav(null)
    $(".viewSettings").addClass("active");
    $("#instanceSettingsBox").show();
    $(".sidebar-fixed").addClass("sidebar-lg-show");
    allInstanceTypes = {};
    currentProvider = null;

    let hosts = '';

    loadInstanceSettings();
    $(".settingsBox").hide();
    $("#instanceSettingsBox").show();
    addBreadcrumbs(["LXDMosaic Settings"], ["active"], true);

}

function makeHostUserOverview(html, host){
    let disabled = "";

    if(host.hostOnline == false){
        disabled = "disabled text-warning text-strikethrough";
    }

    html += `<div class="card mb-2 bg-dark text-white">
        <div class="card-header">
            <h4 class="${disabled}">
                <i class="fas fa-server"></i> ${host.alias}
            </h4>
        </div>
        `;

    if(host.hostOnline === "1"){
        html += `<div class="card-body">
            <table class="table table-bordered table-dark">
            <thead>
                <tr>
                    <th style="width: 10%; max-width: 20%">Project</th>
                    <th style="width: 5%; max-width: 5%">Manage</th>
                    <th style="width: 5%; max-width: 5%"># Users</th>
                    <th style="width: 80%; max-width: 70%">Users</th>
                    </tr>
            </thead>
        <tbody>`;
        $.each(host.projects, function(project, details){
            let noOfUser = details.users.length;
            html += `<tr style="vertical-align: middle">
                <td>${project}</td>
                <td class="text-center"><button class="btn btn-outline-primary btn-sm openProjectAccess text-center" data-host-id="${host.hostId}" data-project="${project}"><i class="fas fa-users"></i></button></td>
                <td class="text-center">${noOfUser}</td>
            `;

            if(noOfUser > 0){
                html += `<td>`
                $.each(details.users.slice(0, 10), (_, user)=>{
                    html += `<div class="badge bg-primary text-white m-2"><i class="fas fa-user me-1"></i>${user.userName}</div>`
                });

                if(noOfUser >= 10){
                    html += `<a href='#'>+ ${noOfUser - 10} more</a>`
                }
                html += `</td>`
            }else{
                html += `<td style="vertical-align: middle"><small><i class="fas fa-info-circle text-secondary me-1"></i>Admins Only</small></td>`
            }

            html += `</tr>`;
        });
        html += "</tbody></table></div>"
    }

    html += "</div>";
    return html;
}

function loadProjectAccesOverview(req){
    $(".settingsBox").hide();
    $("#settingsBox").show();
    putAdminSidebar(adminSettingUrls.userAccessControl);
    changeActiveNav(null)
    $(".viewSettings").addClass("active");
    $("#userProjectOverview").show();
    addBreadcrumbs(["Admin Settings", "Users Projects Access"], ["", "active"], false, ["/admin"]);
    ajaxRequest(globalUrls.projects.getProjectsOverview, {}, (data)=>{
        data = makeToastr(data);
        let hosts = "";
        $.each(data.clusters, (clusterIndex, cluster)=>{
            hosts += `<h4 class="c-sidebar-nav-title text-success pt-2"><u>Cluster ${clusterIndex}</u></h4>`;
            $.each(cluster.members, (_, host)=>{
                hosts = makeHostUserOverview(hosts, host)
            })
        });
        hosts += `<h4 class="c-sidebar-nav-title text-success pt-2"><u>Standalone Hosts</u></h4>`;
        $.each(data.standalone.members, (_, host)=>{
            hosts = makeHostUserOverview(hosts, host)
        });

        $("#allProjectsOverview").empty().append(hosts);
    });
}

function loadRecordedActions(req, ammount = 30){
    $(".settingsBox").hide();
    $("#settingsBox").show();
    putAdminSidebar(adminSettingUrls.history);
    changeActiveNav(null)
    $(".viewSettings").addClass("active");
    $("#recordedActionsBox").show();
    addBreadcrumbs(["Admin Settings", "Recorded Actions"], ["", "active"], false, ["/admin"]);
    $("#actionCount").text(ammount);
    ajaxRequest(globalUrls.settings.recordedActions.getLastResults, {ammount: ammount}, (data)=>{
        data = makeToastr(data);
        let trs = "";
        if(data.length > 0 ){
            $.each(data, (_, item)=>{

                let params = "-";
                let userParams = JSON.parse(item.params)

                let userKeys = Object.keys(item.params);

                if(userKeys.length > 0){
                    params  = ""
                    $.each(userParams, (key, value)=>{
                        if(value == null){
                            return true;
                        }
                        let t = typeof value
                        if(t !== "array" && t !== "object"){
                            params += `<div class="mb-1">
                                ${key}: ${value}
                            </div>`
                        // NOTE 1 level recursion not traversing whole tree's
                        } else if(t === "object"){
                            let pKeys = Object.keys(value);
                            params += `<div class="mb-1">
                                ${key}:
                            </div>`
                            $.each(pKeys, (_, k)=>{
                                params += `<div class="mb-1 ps-2">
                                    ${k}: ${value[k]}
                                </div>`
                            });
                        }
                    });
                }

                trs += `<tr>
                    <td>${item.userName}</td>
                    <td>${moment.utc(item.date).local().fromNow()}</td>
                    <td>${item.controllerName == "" ? item.controller : item.controllerName}</td>
                    <td class="text-break">${params}</td>
                </tr>`;
            });
        }else{
            trs += `<tr><td colspan="999" class="text-info text-center">No Recorded Actions</td></tr>`
        }
        $("#recordedActionsTable > tbody").empty().append(trs);
    });
}

function loadUsers(){
    $(".settingsBox").hide();
    $("#settingsBox").show();
    putAdminSidebar(adminSettingUrls.users);
    changeActiveNav(null)
    $(".viewSettings").addClass("active");
    $("#usersList").show();
    addBreadcrumbs(["Admin Settings", "User Management"], ["", "active"], false, ["/admin"]);
    $("#showDeletedUserFilter").prop("checked", false)
    ajaxRequest(globalUrls.settings.users.getAll, {}, (data)=>{
        data = makeToastr(data);
        let trs = "";
        if(data.length > 0 ){
            $.each(data, (_, user)=>{
                let isAdmin = "times-circle";
                let isAdminTClass = "text-success";

                if(user.isAdmin == 1){
                    isAdmin = "check-circle"
                    isAdminTClass = "text-warning";
                }

                let isDisabled = "times-circle";
                let isDisabledTClass = "text-success";

                if(user.isDisabled == 1){
                    isDisabled = "check-circle"
                    isDisabledTClass = "text-warning";
                }

                let resetPasswordBtn = '<div class="dropdown-item resetPassword" href="#">Reset Password</div>';

                if(parseInt(user.fromLdap) == 1){
                    resetPasswordBtn = '';
                }

                let isDisabledVal = user.isDisabled;
                let isDeleted = user.isDeleted
                let trStyle = isDeleted == 1 ? "display: none;" : ""
                let userIcon = isDeleted == 1 ? '<i class="fas fa-ghost me-2"></i>' : '';

                trs += `<tr data-user-id="${user.id}" data-is-admin="${user.isAdmin}" data-user-name="${user.username}" data-is-disabled=${isDisabledVal} data-is-deleted="${user.isDeleted}" style="${trStyle}">
                    <td><p id="${user.id}" class='viewUser text-primary text-decoration-underline' style="cursor: pointer;">${userIcon}${user.username}</p></td>
                    <td>${moment.utc(user.created).local().fromNow()}</td>
                    <td><i class="fas fa-${isAdmin} ${isAdminTClass}"></i></td>
                    <td><i class="fas fa-${isDisabled} ${isDisabledTClass}"></i></td>
                    <td>
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <div class="dropdown-item setUserProject" href="#">Set Projects Access</div>
                          ${resetPasswordBtn}
                          <div class="dropdown-item toggleUserLogin" href="#">Toggle User Login</div>
                          <div class="dropdown-item toggleUserAdmin" href="#">Toggle User Admin</div>
                          <div class="dropdown-item deleteUser text-danger" href="#">Delete User</div>
                        </div>
                    </td>
                </tr>`;
            });
        }else{
            trs += `<tr><td colspan="999" class="text-info">No Users</td></tr>`
        }
        $("#usersTable > tbody").empty().append(trs);
    });
}

function loadInstancesHostsView(){
    $(".settingsBox").hide();
    $("#settingsBox").show();
    putAdminSidebar(adminSettingUrls.hosts);
    changeActiveNav(null)
    $(".viewSettings").addClass("active");
    $("#instanceHostsBox").show();
    addBreadcrumbs(["Admin Settings", "Hosts Management"], ["", "active"], false, ["/admin"]);
    ajaxRequest(globalUrls.hosts.getAllHosts, {}, (data)=>{
        data = makeToastr(data);
        let trs = "";
        if(data.length > 0 ){
            let now = moment();
            $.each(data, (_, host)=>{
                let o = "<i class='fas fa-check text-success'></i>";
                if(host.hostOnline == 0){
                    o = "<i class='fas fa-times text-danger'></i>";
                }
                let certExpiresIcon = "";
                let certExpires = moment(host.certExpires);
                let threeMonthsPrior = moment(host.certExpires).subtract(3, 'months')

                if(certExpires < now){
                    certExpiresIcon = "<i class='fas fa-exclamation-triangle text-danger me-2'></i>"
                }else if(now > threeMonthsPrior){
                    certExpiresIcon = "<i class='fas fa-exclamation-triangle me-2' style='color: yellow !important;'></i>"
                }

                trs += `<tr id="${host.hostId}">
                    <td>${host.alias}</td>
                    <td>${host.urlAndPort}</td>
                    <td>${o}</td>
                    <td>${certExpiresIcon} ${certExpires.format("ll")}</td>
                        <td>
                            <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <div class='dropdown-item renewCertificate'><i class="fas fa-sync"></i> Renew Certificate</div>
                              <div class='dropdown-item deleteHost'><i class="fas fa-trash"></i> Delete Host</div>
                            </div>
                        </td>
                </tr>`
            });
        }else{
            trs += `<tr><td colspan="999" class="text-info">No Hosts</td></tr>`
        }
        $("#hostListTable > tbody").empty().append(trs);
    });
}

function loadInstanceTypes(){
    $(".settingsBox").hide();
    $("#settingsBox").show();
    putAdminSidebar(adminSettingUrls.instanceTypes);
    changeActiveNav(null)
    $(".viewSettings").addClass("active");
    $("#instanceTypesOverview").show();
    addBreadcrumbs(["Admin Settings", "Instance Types"], ["", "active"], false, ["/admin"]);
    $("#instanceTypesOverviewProviderDetailsSplash").show();
    $("#instanceTypesOverviewProviderDetails").hide();
    ajaxRequest(globalUrls.instances.instanceTypes.getInstanceTypes, {}, function(data){
        data = makeToastr(data);
        allInstanceTypes = data;
        let h = "";
        $.each(data, function(provider, provDetails){
            h += ` <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-dark provider" data-provider-id="${provDetails.providerId}" id="${provider}">
                ${provider}
                <span class="badge bg-primary bg-pill">${provDetails.types.length}</span>
              </li>`
        });
        $("#providersList").empty().append(h);
    });
}

function loadInstanceSettings(){
    ajaxRequest(globalUrls.settings.getAll, {}, (data)=>{
        data = makeToastr(data);
        let trs = "";
        let ldapKeys = [4, 5, 6, 7];
        let ldapTrs = "";
        let ldapExtraText = {
            5: `<i class="fas fa-info-circle text-info ms-2" data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                title="The LDAP user that performs lookups E.G <code>cn=administrator,cn=Users,dc=example,dc=com</code>">
                </i>`,
            7: `<i class="fas fa-info-circle text-info ms-2" data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                title="DN To Search Users for E.G <code>ou=user_folder,dc=example,dc=com</code>">
                </i>`,
        }

        let currentTimezone = "";

        $.each(data.settings, function(_, item){
            if(item.settingId == 11){
                currentTimezone = item.currentValue;
                return true;
            }else if(ldapKeys.includes(parseInt(item.settingId))){
                let extraText = ldapExtraText.hasOwnProperty(item.settingId) ? ldapExtraText[item.settingId] : "";
                let tr = `<tr data-setting-id="${item.settingId}">
                        <td>${item.settingName}${extraText}</td>
                        <td><input class="form-control" name="settingValue" value="${item.currentValue}"/></td>
                    </tr>`;
                ldapTrs += tr;
            }else{
                let tr = `<tr data-setting-id="${item.settingId}">
                        <td>${item.settingName}</td>
                        <td><input class="form-control" name="settingValue" value="${item.currentValue}"/></td>
                    </tr>`;
                trs += tr;
            }
        });

        let timezoneOptions = "<option value=''>Please select</option>";

        $.each(data.timezones, (_, timezone)=>{
            let s = timezone == currentTimezone ? "selected" : "";
            timezoneOptions += `<option value="${timezone}" ${s}>${timezone}</option>`
        });

        trs += `<tr data-setting-id="11">
            <td>Timezone</td>
            <td><select name="settingValue" class="form-select">${timezoneOptions}</select></td>
        </tr>`

        if(data.versionDetails.cantSeeGithub){
            $("#currentVersion").text("Cant see github");
            $("#newVersion").text("Cant see github");
            return false;
        }

        $("#currentVersion").text(data.versionDetails.currentVersion);

        let newVersion = "";

        if(data.versionDetails.newVersionUrl !== false){
            newVersion = `<a target="_blank" href="${data.versionDetails.newVersionUrl}">${data.versionDetails.newVersion}</a>`;
        } else if(data.versionDetails.master == true){
            newVersion = "N/A - You are on the master branch";
        } else if(data.versionDetails.snap == true){
            newVersion = "N/A - Snap will keep you up to date";
        }

        $("#newVersion").html(newVersion);

        $("#ldapSettingListTable > tbody").empty().append(ldapTrs);
        $("#settingListTable > tbody").empty().append(trs);
        $("#ldapSettingListTable").find('[data-bs-toggle="tooltip"]').tooltip({html: true})
    });
}

$("#instanceHostsBox").on("click", "#addHostBtn", function(){
   $.confirm({
            icon: "fa fa-server",
            title: "Add Host",
            columnClass: "col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1",
            content: `<div class="mb-2">
            <label>Alias</label>
            <input class="form-control" name="alias" placeholder="PC, Laptop, Server-1"/>
        </div>
        <label class="mb-2">How Do You Want To Access The Host?</label>
        <nav>
          <div class="nav nav-pills mb-3" id="host-nav" role="tablist">
            <button class="nav-link text-dark  active" id="nav-token-tab" data-bs-toggle="tab" data-bs-target="#nav-token" type="button" role="tab" aria-controls="nav-token" aria-selected="true">Token</button>
            <button class="nav-link text-dark" id="nav-network-tab" data-bs-toggle="tab" data-bs-target="#nav-network" type="button" role="tab" aria-controls="nav-network" aria-selected="true">Trust Password (Depreciated)</button>
            <button class="nav-link text-dark" id="nav-socket-tab" data-bs-toggle="tab" data-bs-target="#nav-socket" type="button" role="tab" aria-controls="nav-socket" aria-selected="false">Socket</button>
          </div>
        </nav>
        <div class="tab-content" id="host-navContent">
          <div class="tab-pane fade show active" id="nav-token" role="tabpanel" aria-labelledby="nav-token-tab">
              <div class="mb-2">
                  <label>IP Address / Hostname</label>
                  <input class="form-control" name="name" placeholder="" autocomplete="off"/>
              </div>
              <div class="mb-2">
                  <label>Token</label>
                  <textarea class="form-control" type="text" name="token" placeholder="" autocomplete="off"></textarea>
              </div>
          </div>
          <div class="tab-pane fade show" id="nav-network" role="tabpanel" aria-labelledby="nav-network-tab">
              <div class="mb-2">
                  <label>IP Address / Hostname</label>
                  <input class="form-control" name="name" placeholder="" autocomplete="off"/>
              </div>
              <div class="mb-2">
                  <label>Trust Password</label>
                  <input class="form-control" type="password" name="trustPassword" placeholder=""/>
              </div>
          </div>
          <div class="tab-pane fade" id="nav-socket" role="tabpanel" aria-labelledby="nav-socket-tab">
              <div class="mb-2">
                  <label>Socket Path</label>
                  <input class="form-control" name="socketPath" value="/var/snap/lxd/common/lxd/unix.socket"/>
              </div>
          </div>
        </div>
        `,
        buttons: {
            cancel: {},
            add: {
                btnClass: "btn-primary",
                action: function(){
                    let alias = this.$content.find('input[name=alias]').val().trim();

                    if(alias === ""){
                        $.alert("Please enter an alias")
                        return false;
                    }

                    let name = ""
                    let trustPassword = ""
                    let token = ""
                    let socketPath = ""

                    let activeNav = this.$content.find("#host-nav .active").data().bsTarget

                    if(activeNav === "#nav-socket"){
                        socketPath = this.$content.find('input[name=socketPath]').val().trim();

                        if(socketPath === ""){
                            $.alert("Please enter socket path")
                            return false;
                        }
                    }else{
                        name = this.$content.find('input[name=name]').val().trim();

                        if(name === ""){
                            $.alert("Please enter ip address / hostname")
                            return false;
                        }
                        if(activeNav == "#nav-token"){
                            token = this.$content.find('textarea[name=token]').val().trim();
                            if(token === ""){
                                $.alert("Please enter token")
                                return false;
                            }
                        }else{
                            trustPassword = this.$content.find('input[name=trustPassword]').val().trim();
                            if(trustPassword === ""){
                                $.alert("Please enter trust password")
                                return false;
                            }
                        }

                    }
                    let x = {hostsDetails: [
                        {
                            name: name,
                            trustPassword: trustPassword,
                            token: token,
                            socketPath: socketPath,
                            alias: alias
                        }
                    ]}

                    ajaxRequest("/api/Hosts/AddHostsController/add", x, function(data){
                        let x = makeToastr(data);
                        if(x.state == "error"){
                            return false;
                        }
                        location.reload();
                    });
                }
            }
        }
    });
});

$("#hostListTable").on("click", ".renewCertificate", function(){
    let tr = $(this).parents("tr");
    let hostId = tr.attr("id");
    $.confirm({
        title: 'Renew Certificate',
        content: 'Renew LXDMosaic certificate with LXD!',
        buttons: {
            cancel: function () {},
            renew: {
                btnClass: 'btn-success',
                action: function () {
                    this.buttons.renew.setText('<i class="fa fa-cog fa-spin"></i>Renewing..'); // let the user know
                    this.buttons.renew.disable();
                    this.buttons.cancel.disable();
                    var modal = this;
                    ajaxRequest('/api/Hosts/Certificates/RenewHostCertificateController/renew', {hostId: hostId}, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            this.buttons.renew.enable();
                            this.buttons.renew.setText('renew'); // let the user know
                            this.buttons.cancel.enable();
                            return false;
                        }
                        window.location.reload()
                        modal.close();
                    });
                    return false;
                }
            }
        }
    });
});

$("#hostListTable").on("click", ".deleteHost", function(){
    let tr = $(this).parents("tr");
    let hostId = tr.attr("id");
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
                    ajaxRequest(globalUrls.hosts.delete, {hostId: hostId}, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            this.buttons.yes.enable();
                            this.buttons.yes.setText('Yes'); // let the user know
                            this.buttons.cancel.enable();
                            return false;
                        }
                        tr.remove();
                        modal.close();
                    });
                    return false;
                }
            }
        }
    });
});

$("#usersTable").on("click", ".deleteUser", function(){
    let tr = $(this).parents("tr");
    let targetUserId = tr.data("userId");
    $.confirm({
        title: "Deleting User: " + $(this).parents("tr").data("userName"),
        content: `<div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="changeUserName" checked>
          <label class="form-check-label" for="changeUserName">Change username to "Deleted User"</label>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="removeProjectAccess" checked>
          <label class="form-check-label" for="removeProjectAccess">Remove all project access</label>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="removeApiKeys" checked>
          <label class="form-check-label" for="removeApiKeys">Remove all API keys</label>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="deleteAuditLogs">
          <label class="form-check-label" for="deleteAuditLogs">Delete user audit logs</label>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="deleteBackupSchedules">
          <label class="form-check-label" for="deleteBackupSchedules">Delete user backup schedules</label>
        </div>`,
        type: "red",
        buttons: {
            cancel: function () {},
            yes: {
                btnClass: 'btn-danger',
                action: function () {
                    this.buttons.yes.setText('<i class="fa fa-cog fa-spin me-2"></i>Deleting User'); // let the user know
                    this.buttons.yes.disable();
                    this.buttons.cancel.disable();
                    var modal = this;
                    var changeUserName = this.$content.find('#changeUserName').is(":checked") ? 1 : 0
                    var removeProjectAccess = this.$content.find('#removeProjectAccess').is(":checked") ? 1 : 0
                    var removeApiKeys = this.$content.find('#removeApiKeys').is(":checked") ? 1 : 0
                    var deleteAuditLogs = this.$content.find('#deleteAuditLogs').is(":checked") ? 1 : 0
                    var deleteBackupSchedules = this.$content.find('#deleteBackupSchedules').is(":checked") ? 1 : 0

                    let x = {
                        targetUserId,
                        changeUserName,
                        removeProjectAccess,
                        removeApiKeys,
                        deleteAuditLogs,
                        deleteBackupSchedules
                    }

                    ajaxRequest('/api/User/DeleteUserController/delete', x, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            this.buttons.yes.enable();
                            this.buttons.yes.setText('Yes'); // let the user know
                            this.buttons.cancel.enable();
                            return false;
                        }
                        tr.remove()
                        modal.close();
                    });
                    return false;
                }
            }
        }
    });
});

$("#usersTable").on("click", ".toggleUserAdmin", function(){
    let tr = $(this).parents("tr");
    let isAdmin = tr.data("isAdmin");
    let title = isAdmin ? "Revoke Admin Privileges?" : "Grant Admin Privileges?";
    let doingTxt = isAdmin ? "Revoking..." : "Granting...";
    // If they are already an admin we are trying to revoke
    let status = isAdmin ? 0 : 1;
    let targetUser = tr.data("userId");
    $.confirm({
        title: title,
        content: 'Are you sure you want todo this?!',
        buttons: {
            cancel: function () {},
            yes: {
                btnClass: 'btn-danger',
                action: function () {
                    this.buttons.yes.setText('<i class="fa fa-cog fa-spin me-2"></i>' + doingTxt); // let the user know
                    this.buttons.yes.disable();
                    this.buttons.cancel.disable();
                    var modal = this;
                    ajaxRequest(globalUrls.settings.users.toggleAdminStatus, {targetUser, status}, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            this.buttons.yes.enable();
                            this.buttons.yes.setText('Yes'); // let the user know
                            this.buttons.cancel.enable();
                            return false;
                        }
                        let isAdmin = "times-circle";
                        let isAdminTClass = "text-success";

                        if(status == 1){
                            isAdmin = "check-circle"
                            isAdminTClass = "text-warning";
                        }

                        tr.find("td:eq(2)").html(`<i class="fas fa-${isAdmin} ${isAdminTClass}"></i>`)
                        tr.data("isAdmin", status);
                        modal.close();
                    });
                    return false;
                }
            }
        }
    });
});

$("#usersTable").on("click", ".toggleUserLogin", function(){
    let tr = $(this).parents("tr");
    let isDisabled = parseInt(tr.data("isDisabled")) === 1
    let title = isDisabled ? "Enable Login?" : "Disable Login?";
    let doingTxt = isDisabled ? "Granting..." : "Revoking...";
    let status = isDisabled ? 0 : 1;
    let targetUser = tr.data("userId");
    $.confirm({
        title: title,
        content: 'Are you sure you want todo this?!',
        buttons: {
            cancel: function () {},
            yes: {
                btnClass: 'btn-danger',
                action: function () {
                    this.buttons.yes.setText('<i class="fa fa-cog fa-spin me-2"></i>' + doingTxt); // let the user know
                    this.buttons.yes.disable();
                    this.buttons.cancel.disable();
                    var modal = this;
                    let x = {targetUser, status};

                    ajaxRequest(globalUrls.settings.users.toggleLoginStatus, x, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            this.buttons.yes.enable();
                            this.buttons.yes.setText('Yes'); // let the user know
                            this.buttons.cancel.enable();
                            return false;
                        }
                        let isDisabledIcon = "times-circle";
                        let isDisabledTClass = "text-success";

                        if(status == 1){
                            isDisabledIcon = "check-circle"
                            isDisabledTClass = "text-warning";
                        }

                        tr.find("td:eq(3)").html(`<i class="fas fa-${isDisabledIcon} ${isDisabledTClass}"></i>`)

                        tr.data("isDisabled", status);
                        modal.close();
                    });
                    return false;
                }
            }
        }
    });
});

function loadRetiredData() {
    $(".settingsBox").hide();
    $("#settingsBox").show();
    putAdminSidebar(adminSettingUrls.retiredData);
    changeActiveNav(null)
    $(".viewSettings").addClass("active");
    $("#retiredData").show();
    addBreadcrumbs(["Admin Settings", "Retired Data"], ["", "active"], false, ["/admin"]);
}

$("#retiredData").on("click", "#downloadOldFleetAnalytics", function(){
    ajaxRequest(globalUrls.analytics.getAllData, {}, (data)=>{
        data = makeToastr(data)
        csv = 'Date,Total Memory Usage,Active Containers,Total Storage Usage,Total Storage Available\n';

        //merge the data with CSV
        data.forEach(function(row) {
            csv += Object.values(row).join(',');
            csv += "\n";
        });

        var hiddenElement = document.createElement('a');
        hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
        hiddenElement.target = '_blank';

        //provide the name for the CSV file to be downloaded
        hiddenElement.download = 'LXDMosaic_Fleet_Analytics.csv';
        hiddenElement.click();
    });
});

$("#instanceTypesOverview").on("click", ".deleteType", function(){
    let tr = $(this).parents("tr");
    let x = {typeId: tr.attr("id")}
    ajaxRequest( globalUrls.instances.instanceTypes.deleteInstanceType, x, (data)=>{
        data = makeToastr(data);
        if(data.hasOwnProperty("error") || data.state == "error"){
            return false;
        }
        tr.remove();
    });
});

$("#instanceTypesOverview").on("click", "#addInstanceType", function(){
    $.confirm({
        title: 'Create Instance Type!',
        content: `
        <div class="mb-2">
            <label>Name</label>
            <input class="form-control" name="name"/>
        </div>
        <div class="mb-2">
            <label>CPU Cores Limit (E.G 2.0)</label>
            <input class="form-control" name="cpu"/>
        </div>
        <div class="mb-2">
            <label>Memory Limit (In GB)</label>
            <input class="form-control" name="memory"/>
        </div>
        `,
        buttons: {
            cancel: function () {
                //close
            },
            formSubmit: {
                text: 'Create',
                btnClass: 'btn-blue',
                action: function () {
                    var name = this.$content.find('input[name=name]').val().trim();
                    var cpu = this.$content.find('input[name=cpu]').val().trim();
                    var memory = this.$content.find('input[name=memory]').val().trim();
                    if(name == ""){
                        $.alert('Please provide a name');
                        return false;
                    }else if(cpu == ""){
                        $.alert('Please provide a cpu limit');
                        return false;
                    }else if(memory == ""){
                        $.alert('Please provide a memory limit');
                        return false;
                    }
                    let x = {name, cpu, mem: memory, providerId: currentProvider};
                    ajaxRequest(globalUrls.instances.instanceTypes.addInstanceType, x, (response)=>{
                        response = makeToastr(response);
                        if(response.state == "error"){
                            return false;
                        }
                        loadInstanceTypes();
                    });
                }
            }
        }
    });
});
$("#instanceTypesOverview").on("click", "#addProvider", function(){
    $.confirm({
        title: 'Create Instance Type!',
        content: `
        <div class="mb-2">
            <label>Name</label>
            <input class="form-control" name="name"/>
        </div>
        `,
        buttons: {
            cancel: function () {
                //close
            },
            formSubmit: {
                text: 'Create',
                btnClass: 'btn-blue',
                action: function () {
                    var name = this.$content.find('input[name=name]').val().trim();

                    if(name == ""){
                        $.alert('Please provide a name');
                        return false;
                    }
                    let x = {name};
                    ajaxRequest(globalUrls.instances.instanceTypes.providers.add, x, (response)=>{
                        response = makeToastr(response);
                        if(response.state == "error"){
                            return false;
                        }
                        loadInstanceTypes();
                    });
                }
            }
        }
    });
});

$("#instanceTypesOverview").on("click", "#deleteProvider", function(){
    $.confirm({
        title: 'Delete Provider ?!',
        content: `Deleting a provider will delete all instance types.`,
        buttons: {
            cancel: function () {
                //close
            },
            formSubmit: {
                text: 'Im Sure',
                btnClass: 'btn-danger',
                action: function () {
                    let x = {providerId: currentProvider};
                    ajaxRequest(globalUrls.instances.instanceTypes.providers.removeProvider, x, (response)=>{
                        response = makeToastr(response);
                        if(response.state == "error"){
                            return false;
                        }
                        loadInstanceTypes()
                    });
                }
            }
        }
    });
});

$("#instanceTypesOverview").on("click", ".provider", function(){
    $("#providersList").find(".active").removeClass("active");
    $(this).addClass("active");
    currentProvider = $(this).data("providerId")
    let types = allInstanceTypes[$(this).attr("id")].types
    $("#providerName").text($(this).attr("id"));
    let trs = "";
    $.each(types, (_, type)=>{
        trs += `<tr id="${type.id}">
            <td>${type.instanceName}</td>
            <td>${type.cpu}</td>
            <td>${type.mem}</td>
            <td><button class="btn btn-danger deleteType"><i class="fas fa-trash"></i></button></td>
        </tr>`
    });
    $("#providerTypesTable > tbody").empty().append(trs);
    $("#instanceTypesOverviewProviderDetailsSplash").hide();
    $("#instanceTypesOverviewProviderDetails").show();
});

$("#usersList").on("click", ".viewUser", function(){
    let x = {targetUser: $(this).attr("id") }
    let userName = $(this).text();

    $("#userDetails").show();
    addBreadcrumbs(["Users", userName], ["", "active"], true, ["/admin/users", ""]);
    ajaxRequest(globalUrls.user.getUserOverview, x, (data)=>{
        data = makeToastr(data);
        let x = `<div class="mb-3 text-center">
            <h4 class="text-underline">
                <i class="fas fa-user me-2"></i>Viewing <code>${userName}</code> latest attempted actions
            </h4>
        </div>`;

        let categoryIcons = {
            "instance": "fas fa-box",
            "backups": "fas fa-save",
            "projects": "fas fa-project-diagram",
            "profiles": "fas fa-users",
            "networks": "fas fa-network-wired",
            "storage": "fas fa-hdd"

        };

        let methodIcons = {
            "create": "fas fa-plus",
            "delete": "fas fa-trash",
            "schedule": "fas fa-clock",
            "disable": "fas fa-power-off"
        };

        if(data.length == 0){
            x += `<div class="text-center"><h5><i class="fas fa-info-circle text-info me-2"></i>No Recorded Actions</h5></div>`;
        }else{
            $.each(data, (category, methods)=>{
                x += `<div class='mb-3 pb-2 border-bottom'>
                    <h1><i class="${categoryIcons[category]} me-2"></i>${category}
                    </h1>
                    <div class="row">`
                let c = Math.floor(12 / Object.keys(methods).length);
                let i = 0 ;
                $.each(methods, (method, events)=>{
                    let bl = i == 0 ? "" : "border-start";
                    x += `<div class="col-md-${c} ${bl} text-center"><h4><i class="${methodIcons[method]} me-2"></i> ${method} - ${events.length}</h4></div>`;
                    i++;
                });
                x += `</div></div>`
            });
        }

        $("#userDetails").empty().append(x);

    });
});

$("#recordedActionsBox").on("click", "#loadMoreRecordedActions", function(){
    $.confirm({
    title: 'Prompt!',
    content: `
    <div class="alert alert-danger">
        Loading to many logs may crash your browser!
    </div>
    <form action="" class="formName">
        <div class="mb-2">
            <label>Ammount Of Logs To Fetch</label>
            <input type="text" value="30" class="ammount form-control" required />
        </div>
    </form>`,
    buttons: {
        formSubmit: {
            text: 'Submit',
            btnClass: 'btn-blue',
            action: function () {
                var ammount = this.$content.find('.ammount').val();
                if(!$.isNumeric(ammount)){
                    $.alert('provide a valid ammount');
                    return false;
                }
                loadRecordedActions({}, ammount)
            }
        },
        cancel: function () {
            //close
        },
    },
    onContentReady: function () {
        // bind to events
        var jc = this;
        this.$content.find('form').on('submit', function (e) {
            // if the user submits the form by pressing enter in the field.
            e.preventDefault();
            jc.$$formSubmit.trigger('click'); // reference the button and click it
        });
    }
});
});

$("#usersList").on("click", "#addUser", function(){
    $.confirm({
        title: 'Create user!',
        content: `<form action="" class="formName">
            <div class="mb-2">
                <label>Username</label>
                <input type="text" class="form-control" name="username" required />
            </div>
            <div class="mb-2">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required />
            </div>
            <i class="fas fa-info-circle text-warning me-2"></i>Users wont be able to login until you grant access to projects!
        </form>`,
        buttons: {
            cancel: {
                action: function(){}
            },
            create: {
                text: 'create',
                btnClass: 'btn-blue',
                action: function () {
                    let username = this.$content.find('input[name=username]').val().trim();
                    let password = this.$content.find('input[name=password]').val().trim();
                    if(username == ""){
                        $.alert('provide a username');
                        return false;
                    } else if(password == ""){
                        $.alert('provide a password');
                        return false;
                    }

                    ajaxRequest(globalUrls.settings.users.add, {username: username, password: password}, (data)=>{
                        data = makeToastr(data);
                        if(data.hasOwnProperty("state") && data.state == "error"){
                            return false;
                        }
                        loadUsers();
                    });
                }
            },
            cancel: function () {
                //close
            },
        }
    });
});

$("#usersCard").on("keyup", "#filterUsersTable", function(){
    let search = $(this).val().trim().toLowerCase()
    if(search == ""){
        $("#usersTable > tbody > tr").show()
        return false;
    }
    let showDeleted = $("#showDeletedUserFilter").is(":checked")
    $("#usersTable > tbody > tr").each(function(){
        let name = $(this).data("userName")
        let deleted = $(this).data("isDeleted")
        if(name.includes(search) && (!isDeleted || showDeleted)){
            $(this).show();
        }else{
            $(this).hide();
        }
    });
});

$("#usersCard").on("change", "#showDeletedUserFilter", function(){
    if($(this).is(":checked")){
        $("#usersList").find("[data-is-deleted=1]").show()
    }else{
        $("#usersList").find("[data-is-deleted=1]").hide()
    }
});

$("#usersList").on("click", ".setUserProject", function(){
    setUserSettings.targetUser = $(this).parents("tr").data("userId");
    $("#modal-settings-setUserProject").modal("show");
});

$("#usersList").on("click", ".resetPassword", function(e){
    let targetUser = $(this).parents("tr").data("userId");
    $.confirm({
        backgroundDismiss: true,
        title: 'Reset password!',
        content: `<form>
            <div class="mb-2">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required />
            </div>
        </form>`,
        buttons: {
            cancel: {
                action: function(){}
            },
            reset: {
                text: 'reset',
                btnClass: 'btn-blue',
                keys: ['enter'],
                action: function () {
                    let password = this.$content.find('input[name=password]').val().trim();
                    if(password == ""){
                        $.alert('provide a password');
                        return false;
                    }

                    ajaxRequest(globalUrls.settings.users.resetPassword, {targetUser: targetUser, newPassword: password}, (data)=>{
                        data = makeToastr(data);
                        if(data.hasOwnProperty("state") && data.state == "error"){
                            return false;
                        }
                        loadUsers();
                    });
                }
            }
        },
        onContentReady: function () {
            // bind to events
            var jc = this;
            this.$content.find('form').on('submit', function (e) {
                // if the user submits the form by pressing enter in the field.
                e.preventDefault();
                jc.$$reset.trigger('click'); // reference the button and click it
            });
        }
    });
});

$("#instanceSettingsBox").on("click", "#saveSettings", function(){
    let settings = [];
    $("#settingListTable > tbody > tr").each(function(){
        let tr = $(this);

        settings.push({
            id: tr.data("settingId"),
            value: tr.find("[name=settingValue]").val()
        });
    });
    ajaxRequest(globalUrls.settings.saveAll, {settings: settings}, (data)=>{
        makeToastr(data)
    });
});
$("#instanceSettingsBox").on("click", "#saveLdapSettings", function(){
    let settings = [];
    $("#ldapSettingListTable > tbody > tr").each(function(){
        let tr = $(this);

        settings.push({
            id: tr.data("settingId"),
            value: tr.find("input[name=settingValue]").val()
        });
    });
    ajaxRequest(globalUrls.settings.ldap.save, {settings: settings}, (data)=>{
        makeToastr(data)
    });
});
</script>

<?php
    require __DIR__ . "/../modals/users/projectAccess.php";
?>
