<div id="profileBox" class="boxSlide">
<div id="profileOverview" class="row">
    <div class="col-md-9">
          <div class="card">
            <div class="card-header bg-info" role="tab" id="headingOne">
              <h5>
                <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                  Profiles
                </a>
              </h5>
            </div>
            <div id="cloudConfigDescription" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
              <div class="card-block bg-dark">
                  Profiles are used to store processes used with a container.
              </div>
            </div>
          </div>
    </div>
</div>
<div id="profileDetails" class="row">
<div class="col-md-3">
      <div class="card">
        <div class="card-header bg-info" role="tab" id="profilesActionHeading">
          <h5>
            <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#profileActions" aria-expanded="true" aria-controls="profileActions">
              Actions
              <i class="fas fa-edit float-right"></i>
            </a>
          </h5>
        </div>
        <div id="profileActions" class="collapse show" role="tabpanel" aria-labelledby="profilesActionHeading">
          <div class="card-block bg-dark table-responsive">
              <div id="collapseOne" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
                <div class="card-block">
                    <button class="btn btn-block btn-primary" id="copyProfile">
                        Copy
                    </button>
                    <button class="btn btn-block btn-warning" id="renameProfile">
                        Rename
                    </button>
                    <button class="btn btn-block btn-danger" id="deleteProfile">
                        Delete
                    </button>
                    <hr/>
                </div>
              </div>
          </div>
        </div>
      </div>
</div>
<div class="col-md-6">
      <div class="card">
        <div class="card-header bg-info" role="tab" id="profileDevicesHeading">
          <h5>
            <a id="devicesAriaToggle" class="text-white" data-toggle="collapse" data-parent="#profileDevicesHeading" href="#profileDevicesCard" aria-expanded="true" aria-controls="profileDevicesCard">
              Devices
              <i class="nav-icon fa fa-cog float-right"></i>
            </a>
          </h5>
        </div>
        <div id="profileDevicesCard" class="collapse show" role="tabpanel" aria-labelledby="profileDevicesHeading">
          <div class="card-block bg-dark table-responsive">
              <table class="table table-bordered table-dark" id="profile-deviceData">
                    <thead class="thead-inverse">
                        <tr>
                            <th> Key </th>
                            <th> Value </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
              </table>
          </div>
        </div>
      </div>
      <br/>
      <div class="card">
        <div class="card-header bg-info" role="tab" id="configDeviceCardHeading">
          <h5>
            <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#configDeviceCard" aria-expanded="true" aria-controls="configDeviceCard">
              Configuration
              <i class="fas fa-cogs float-right"></i>
            </a>
          </h5>
        </div>

        <div id="configDeviceCard" class="collapse show" role="tabpanel" aria-labelledby="configDeviceCardHeading">
          <div class="card-block bg-dark table-responsive">
              <table class="table table-dark table-striped" id="profile-configData">
                    <thead class="thead-inverse">
                        <tr>
                            <th> Key </th>
                            <th> Value </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
              </table>
          </div>
        </div>
      </div>
</div>
<div class="col-md-3">
      <div class="card">
        <div class="card-header bg-info" role="tab" id="usedByCard">
          <h5>
            <a data-toggle="collapse" class="text-white" data-parent="#accordion" href="#usedByCollapse" aria-expanded="true" aria-controls="usedByCollapse">
              Used By
              <i class="fas fa-users float-right"></i>
            </a>
          </h5>
        </div>

        <div id="usedByCollapse" class="collapse show bg-dark" role="tabpanel" aria-labelledby="usedByCard">
          <div class="card-block bg-dark table-responsive">
              <table class="table table-bordered table-dark" id="profile-usedByData">
                    <thead class="thead-inverse">
                        <tr>
                            <th> Counter </th>
                            <th> Name </th>
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

<script>

function loadProfileView(selectedProfile = null, selectedHost = null, callback = null)
{
    changeActiveNav(".viewProfiles")
    ajaxRequest(globalUrls.profiles.getAllProfiles, null, function(data){
        var data = $.parseJSON(data);

        let a = selectedProfile == null ? "active" : "";
        let hosts = `
        <li class="nav-item ${a} profile-overview">
            <a class="nav-link" href="#">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>`;
        $.each(data, function(hostName, host){
            if(host.online == false){
                hostName += " (Offline)";
            }

            hosts += `<li class="nav-item nav-dropdown open">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fas fa-server"></i> ${hostName}
                </a>
                <ul class="nav-dropdown-items">`;

            $.each(host.profiles, function(profileName, details){
                let active = "";
                if(host.hostId == selectedHost && profileName == selectedProfile ){
                    active = "active";
                }

                hosts += `<li class="nav-item view-profile ${active}"
                    data-host-id="${host.hostId}"
                    data-profile="${profileName}"
                    data-alias="${hostName}">
                  <a class="nav-link" href="#">
                    <i class="nav-icon fa fa-user"></i>
                    ${profileName}
                  </a>
                </li>`;
            });
            hosts += "</ul></li>";
        });
        $(".boxSlide, #profileDetails").hide();
        $("#profileOverview, #profileBox").show();
        $("#sidebar-ul").empty().append(hosts);

        profileData = data;
        if($.isFunction(callback)){
            callback();
        }
    });
}

$("#sidebar-ul").on("click", ".view-profile", function(){
    viewProfile($(this).data("profile"), $(this).data("alias"), $(this).data("hostId"));
})

function viewProfile(profileId, hostAlias, hostId){
    currentProfileDetails.profile = profileId;
    currentProfileDetails.hostAlias = hostAlias;
    currentProfileDetails.hostId = hostId;
    let details = profileData[hostAlias].profiles[profileId].details;
    let deviceTableRows = createTableRowsHtml(details.devices);

    addBreadcrumbs(["Profiles", hostAlias, profileId], ["", "", "active"], false);

    let usedBy = [{empty: "Couldn't get profiles uesd by (api version probably)"}];

    if(details.hasOwnProperty("used_by")){
        usedBy = details.used_by;
    }

    let profileUsedByHtml = createTableRowsHtml(usedBy);
    let configTr = createTableRowsHtml(details.config);

    let collpaseDetailsFunc = $.isEmptyObject(details.devices) ? "hide" : "show";
    let collpaseConfigFunc = $.isEmptyObject(details.config) ? "hide" : "show";

    $("#profileDevicesCard").collapse(collpaseDetailsFunc);
    $("#configDeviceCard").collapse(collpaseConfigFunc);

    $("#profileBox #deleteProfile").attr("disabled", usedBy.length > 0);
    $("#profile-deviceData > tbody").empty().html(deviceTableRows);
    $("#profile-usedByData > tbody").empty().html(profileUsedByHtml);
    $("#profile-configData > tbody").empty().html(configTr);
    $("#profileOverview").hide();
    $(".boxSlide").hide();
    $("#profileDetails, #profileBox").show();
}


$("#profileBox").on("click", "#copyProfile", function(){
    copyProfileData.hostAlias = currentProfileDetails.hostAlias;
    copyProfileData.hostId = currentProfileDetails.hostId;
    copyProfileData.profile = currentProfileDetails.profile;
    $("#modal-profile-copy").modal("show");
});

$("#profileBox").on("click", "#renameProfile", function(){
    renameProfileData.hostAlias = currentProfileDetails.hostAlias;
    renameProfileData.hostId = currentProfileDetails.hostId;
    renameProfileData.currentName = currentProfileDetails.profile;
    $("#modal-profile-rename").modal("show");
});

$("#profileBox").on("click", "#deleteProfile", function(){
    $.confirm({
        title: 'Delete profile ' + currentProfileDetails.profile + ' from ' + currentProfileDetails.hostAlias,
        content: 'Are you sure you want to delete this profile ?!',
        buttons: {
            cancel: function () {},
            delete: {
                btnClass: 'btn-danger',
                action: function () {
                    ajaxRequest(globalUrls.profiles.delete, currentProfileDetails, function(data){
                        let r = makeToastr(data);
                        if(r.state == "success"){
                            loadProfileView();
                        }
                    });
                }
            }
        }
    });
});
</script>

<?php
require __DIR__ . "/../modals/profiles/rename.php";
require __DIR__ . "/../modals/profiles/copy.php";
?>
