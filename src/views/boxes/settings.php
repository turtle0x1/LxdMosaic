<div id="settingsBox" class="boxSlide">
    <div id="settingsOverview" class="settingsBox">
        <div class="row">
            <div class="col-md-6">
                  <div class="card bg-dark">
                  <div class="card-header d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center" role="tab" >
                      <h5>
                        <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#currentSettingsTable" aria-expanded="true" aria-controls="currentSettingsTable">
                            My Permanent API keys
                        </a>
                        </h5>
                        <div class="btn-toolbar float-right">
                            <div class="btn-group mr-2">
                                <button class="btn btn-success" id="createToken">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="" class="collapse in show" role="tabpanel" >
                      <div class="card-body bg-dark">
                        <table class="table table-dark table-bordered" id="apiKeyTable">
                            <thead>
                                <tr>
                                    <th>Date Created</th>
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
            <div class="col-md-4">
                  <div class="card bg-dark">
                    <div class="card-header bg-dark" role="tab" >
                      <h5>
                        <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#currentSettingsTable" aria-expanded="true" aria-controls="currentSettingsTable">
                          LXDMosaic Details
                        </a>
                      </h5>
                    </div>
                    <div id="currentSettingsTable" class="collapse in show" role="tabpanel" >
                      <div class="card-body bg-dark">
                        <table class="table table-dark table-bordered" id="">
                            <tbody>
                                <tr>
                                    <th>Current Version</th>
                                    <td id="currentVersion"></td>
                                </tr>
                                <tr>
                                    <th>New Version</th>
                                    <td id="newVersion"></td>
                                </tr>
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
              </div>
         </div>
</div>
<div id="userDetails" class="settingsBox">
</div>
<div id="instanceSettingsBox" class="settingsBox row">
    <div class="col-md-6">
      <div class="card bg-dark">
      <div class="card-header d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center" role="tab" >
          <h5>
            <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#currentSettingsTable" aria-expanded="true" aria-controls="currentSettingsTable">
              General Settings
            </a>
            </h5>
            <div class="btn-toolbar float-right">
                <div class="btn-group mr-2">
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
      <div class="card bg-dark">
      <div class="card-header d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center" role="tab" >
          <h5>
            <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#currentSettingsTable" aria-expanded="true" aria-controls="currentSettingsTable">
              LDAP Settings
            </a>
            </h5>
            <div class="btn-toolbar float-right">
                <div class="btn-group mr-2">
                    <button class="btn btn-success" id="saveLdapSettings">
                        <i class="fas fa-save"></i>
                    </button>
                </div>
            </div>
        </div>
        <div id="" class="collapse in show" role="tabpanel" >
          <div class="card-body bg-dark">
            <div class="text-center mb-2">
                <small class="font-italic"><i class="fas fa-info-circle text-info mr-2"></i>Users sync'd on the hour every hour!</small>
            </div>
            <div class="text-center mb-2">
                <small class="font-italic"><i class="fas fa-info-circle text-warning mr-2"></i>Setting LDAP Server to empty value will disable LDAP syncing &amp; login!</small>
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
<div id="recordedActionsBox" class="settingsBox">
    <div class="card bg-dark" id="recordedActionsCard">
      <div class="card-header bg-dark" role="tab" >
        <h5>
          <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#lastRecordedActions" aria-expanded="true" aria-controls="lastRecordedActions">
            Last <span id="actionCount"></span> Recorded Actions
          </a>
          <button class="btn btn-primary float-right" id="loadMoreRecordedActions">
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
    <div class="card bg-dark" id="recordedActionsCard">
      <div class="card-header bg-dark" role="tab" >
        <h5>
          <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#allHostsList" aria-expanded="true" aria-controls="allHostsList">
            Hosts
          </a>
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
<div id="usersList" class="settingsBox">
    <div class="card bg-dark" id="usersCard">
      <div class="card-header" role="tab" >
        <h5>
          <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#users" aria-expanded="true" aria-controls="users">
            Users
          </a>
          <button class="btn btn-primary float-right" id="addUser">
              <i class="fas fa-plus"></i>
          </button>
        </h5>
      </div>
      <div id="users" class="collapse in show" role="tabpanel">
        <div class="card-body">
          <table class="table table-dark table-bordered" id="usersTable">
              <thead>
                  <tr>
                      <th>User</th>
                      <th>Added</th>
                      <th>Admin</th>
                      <th>Projects</th>
                      <th>Reset Password</th>
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

<script>

function loadSettingsView()
{
    $(".boxSlide").hide();
    $(".settingsBox").hide();
    $("#settingsOverview, #settingsBox").show();
    $(".sidebar-fixed").addClass("sidebar-lg-show");

    let hosts = `
    <li class="nav-item my-settings">
        <a class="nav-link text-info" href="#">
            <i class="fas fa-user mr-2"></i>My Settings
        </a>
    </li>`

    if(!userDetails.isAdmin){
        $("#saveSettings, #saveLdapSettings, #addUser, #recordedActionsCard, #usersCard").remove();
    }else{
        hosts += `
        <li class="nav-item instance-hosts">
            <a class="nav-link" href="#">
                <i class="fas fa-server mr-2"></i>Hosts
            </a>
        </li>
        <li class="nav-item instance-settings">
            <a class="nav-link" href="#">
                <i class="fas fa-sliders-h mr-2"></i>LXDMosaic Settings
            </a>
        </li>
        <li class="nav-item users-overview">
            <a class="nav-link" href="#">
                <i class="fas fa-users-cog mr-2"></i>Users
            </a>
        </li>
        <li class="nav-item recorded-actions-overview">
            <a class="nav-link" href="#">
                <i class="fas fa-history mr-2"></i>Recorded Actions
            </a>
        </li>`
    }

    $("#sidebar-ul").empty().append(hosts);

    addBreadcrumbs(["Settings", "My Settings"], ["viewSettings", "active"], false);

    ajaxRequest(globalUrls.settings.getOverview, {}, (data)=>{
        data = makeToastr(data);
        if(data.hasOwnProperty("state") && data.state == "error"){
            return false;
        }

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

        let trs = "";

        if(data.permanentTokens.length == 0){
            trs = "<tr><td colspan='999'>No Permanent API Keys!</td></tr>"
        }else{
            $.each(data.permanentTokens, (_, token)=>{
                trs += `<tr>
                    <td>${moment(token.created).format("llll")}</td>
                    <td><button class="btn btn-danger btn-sm deleteToken" id="${token.id}" ><i class="fas fa-trash"></i></button></td>
                </tr>`;
            });
        }

        $("#apiKeyTable > tbody").empty().append(trs);
    });

}

function loadRecordedActions(ammount = 30){
    $("#actionCount").text(ammount);
    ajaxRequest(globalUrls.settings.recordedActions.getLastResults, {ammount: ammount}, (data)=>{
        data = $.parseJSON(data);
        let trs = "";
        if(data.length > 0 ){
            $.each(data, (_, item)=>{
                trs += `<tr>
                    <td>${item.userName}</td>
                    <td>${moment(item.date).fromNow()}</td>
                    <td>${item.controllerName == "" ? item.controller : item.controllerName}</td>
                    <td class="text-break">${item.params}</td>
                </tr>`;
            });
        }else{
            trs += `<tr><td colspan="999" class="text-info text-center">No Recorded Actions</td></tr>`
        }
        $("#recordedActionsTable > tbody").empty().append(trs);
    });
}

function loadUsers(){
    ajaxRequest(globalUrls.settings.users.getAll, {}, (data)=>{
        data = $.parseJSON(data);
        let trs = "";
        if(data.length > 0 ){
            $.each(data, (_, user)=>{
                let isAdmin = user.isAdmin == 1  ? "check-circle" : "times-circle";
                trs += `<tr data-user-id="${user.id}">
                    <td><a href="#" id="${user.id}" class='viewUser'>${user.username}</a></td>
                    <td>${moment(user.created).fromNow()}</td>
                    <td><i class="fas fa-${isAdmin}"></i></td>
                    <td>
                        <button class="btn btn-primary setUserProject">
                            <i class="fas fa-wrench"></i>
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-primary resetPassword">
                            <i class="fas fa-wrench"></i>
                        </button>
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
    ajaxRequest(globalUrls.hosts.getAllHosts, {}, (data)=>{
        data = $.parseJSON(data);
        let trs = "";
        if(data.length > 0 ){
            $.each(data, (_, host)=>{
                let o = "<i class='fas fa-check text-success'></i>";
                if(host.Host_Online == 0){
                    o = "<i class='fas fa-times text-danger'></i>";
                }
                trs += `<tr id="${host.Host_ID}">
                    <td>${host.Host_Alias}</td>
                    <td>${host.Host_Url_And_Port}</td>
                    <td>${o}</td>
                    <td><button class='btn btn-danger deleteHost'><i class="fas fa-trash"></i></button></td>
                </tr>`
            });
        }else{
            trs += `<tr><td colspan="999" class="text-info">No Hosts</td></tr>`
        }
        $("#hostListTable > tbody").empty().append(trs);
    });
}

function loadInstanceSettings(){
    ajaxRequest(globalUrls.settings.getAll, {}, (data)=>{
        data = makeToastr(data);
        let trs = "";
        let ldapKeys = [4, 5, 6, 7];
        let ldapTrs = "";
        let ldapExtraText = {
            5: `<i class="fas fa-info-circle text-info ml-2" data-toggle="tooltip"
                data-placement="bottom"
                title="The LDAP user that performs lookups E.G <code>cn=administrator,cn=Users,dc=example,dc=com</code>">
                </i>`,
            7: `<i class="fas fa-info-circle text-info ml-2" data-toggle="tooltip"
                data-placement="bottom"
                title="DN To Search Users for E.G <code>ou=user_folder,dc=example,dc=com</code>">
                </i>`,
        }

        $.each(data, function(_, item){

            if(ldapKeys.includes(parseInt(item.settingId))){
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
        $("#ldapSettingListTable > tbody").empty().append(ldapTrs);
        $("#settingListTable > tbody").empty().append(trs);
        $("#ldapSettingListTable").find('[data-toggle="tooltip"]').tooltip({html: true})
    });
}

$("#sidebar-ul").on("click", ".instance-hosts", function(e){
    $(".settingsBox").hide();
    $("#instanceHostsBox").show();
    addBreadcrumbs(["Hosts Management"], ["active"], true);
    loadInstancesHostsView();
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

$("#sidebar-ul").on("click", ".my-settings", function(e){
    e.preventDefault();
    e.stopImmediatePropagation();
    loadSettingsView();
});

$("#sidebar-ul").on("click", ".instance-settings", function(){
    loadInstanceSettings();
    $(".settingsBox").hide();
    $("#instanceSettingsBox").show();
    addBreadcrumbs(["Instance Management"], ["active"], true);
});

$("#sidebar-ul").on("click", ".users-overview", function(){
    loadUsers();
    $(".settingsBox").hide();
    $("#usersList").show();
    addBreadcrumbs(["User Management"], ["active"], true);
});

$("#sidebar-ul").on("click", ".recorded-actions-overview", function(){
    loadRecordedActions();
    $(".settingsBox").hide();
    $("#recordedActionsBox").show();
    addBreadcrumbs(["Recorded Actions"], ["active"], true);
});

$("#usersList").on("click", ".viewUser", function(){
    let x = {targetUser: $(this).attr("id") }
    let userName = $(this).text();

    $("#settingsOverview").hide();
    $("#userDetails").show();
    addBreadcrumbs(["Users", userName], ["", "active"]);
    ajaxRequest(globalUrls.user.getUserOverview, x, (data)=>{
        data = makeToastr(data);
        let x = `<div class="mb-3 text-center">
            <h4 class="text-underline">
                <i class="fas fa-user mr-2"></i>Viewing <code>${userName}</code> latest attempted actions
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
            x += `<div class="text-center"><h5><i class="fas fa-info-circle text-info mr-2"></i>No Recorded Actions</h5></div>`;
        }else{
            $.each(data, (category, methods)=>{
                x += `<div class='mb-3 pb-2 border-bottom'>
                    <h1><i class="${categoryIcons[category]} mr-2"></i>${category}
                    </h1>
                    <div class="row">`
                let c = Math.floor(12 / Object.keys(methods).length);
                let i = 0 ;
                $.each(methods, (method, events)=>{
                    let bl = i == 0 ? "" : "border-left";
                    x += `<div class="col-md-${c} ${bl} text-center"><h4><i class="${methodIcons[method]} mr-2"></i> ${method} - ${events.length}</h4></div>`;
                    i++;
                });
                x += `</div></div>`
            });
        }

        $("#userDetails").empty().append(x);

    });
});

$("#settingsOverview").on("click", "#createToken", function(){
    $.confirm({
        title: 'Create Permanent API Key!',
        content: `
        <div class="form-group">
            <label>Token</label>
            <input class="form-control" name="token"/>
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
                    var token = this.$content.find('input[name=token]').val().trim();
                    if(token == ""){
                        $.alert('Please provide a token');
                        return false;
                    }
                    let x = {token: token};
                    ajaxRequest(globalUrls.user.tokens.create, x, (response)=>{
                        response = makeToastr(response);
                        if(response.state == "error"){
                            return false;
                        }
                        tr.remove();
                    });
                }
            }
        },
        onContentReady: function () {
            // bind to events
            var jc = this;
            this.$content.find('input[name=token]').val(Math.random().toString(36).substring(2));
        }
    });
});

$("#settingsOverview").on("click", ".deleteToken", function(){
    let tr = $(this).parents("tr");
    let x = {tokenId: $(this).attr("id")};
    ajaxRequest(globalUrls.user.tokens.delete, x, (response)=>{
        response = makeToastr(response);
        if(response.state == "error"){
            return false;
        }
        tr.remove();
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
        <div class="form-group">
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
                loadRecordedActions(ammount)
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
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" required />
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required />
            </div>
        </form>`,
        buttons: {
            formSubmit: {
                text: 'Submit',
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

$("#usersList").on("click", ".setUserProject", function(){
    setUserSettings.targetUser = $(this).parents("tr").data("userId");
    $("#modal-settings-setUserProject").modal("show");
});

$("#usersList").on("click", ".resetPassword", function(){
    let targetUser = $(this).parents("tr").data("userId");
    $.confirm({
        title: 'Reset password!',
        content: `<form action="" class="formName">
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required />
            </div>
        </form>`,
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
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
            },
            cancel: function () {
                //close
            },
        }
    });
});

$("#settingsOverview").on("click", "#saveSettings", function(){
    let settings = [];
    $("#settingListTable > tbody > tr").each(function(){
        let tr = $(this);

        settings.push({
            id: tr.data("settingId"),
            value: tr.find("input[name=settingValue]").val()
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
