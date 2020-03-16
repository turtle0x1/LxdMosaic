<div id="profileBox" class="boxSlide">
<div id="profileOverview" class="row">
    <div class="col-md-9">
          <div class="card">
            <div class="card-header bg-info" role="tab" >
              <h5>
                <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                  Profiles
                </a>
              </h5>
            </div>
            <div id="cloudConfigDescription" class="collapse in show" role="tabpanel" >
              <div class="card-block bg-dark">
                  Profiles are used to store processes used with a container.
              </div>
            </div>
          </div>
    </div>
</div>
<div id="profileDetails">
    <div class="row border-bottom">
        <div class="col-md-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
            <h4 id="profileNameTitle"> <u>
            </u></h4>
            <div class="btn-toolbar float-right">
              <div class="btn-group mr-2">
                  <button class="btn btn-primary" id="copyProfile">
                      Copy
                  </button>
                  <button class="btn btn-warning" id="renameProfile">
                      Rename
                  </button>
                  <button class="btn btn-danger" id="deleteProfile">
                      Delete
                  </button>
              </div>
            </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
<div class="col-md-4">
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
</div>
<div class="col-md-5">
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
</div>

<script>

var currentProfileDetails = {
    profile: null,
    host: null
};

function makeHostHtml(hosthtml, host, selectedProfile = null, selectedHost = null){
    let disabled = "";
    if(host.online == false){
        disabled = "disabled text-warning text-strikethrough";
    }

    hosthtml += `<li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle ${disabled}" href="#">
            <i class="fas fa-server"></i> ${host.hostAlias}
        </a>
        <ul class="nav-dropdown-items">`;

    $.each(host.profiles, function(profileName, details){
        let active = "";
        if(host.hostId == selectedHost && profileName == selectedProfile ){
            active = "text-info";
        }

        hosthtml += `<li class="nav-item view-profile ${active}"
            data-host-id="${host.hostId}"
            data-profile="${profileName}"
            data-alias="${host.hostAlias}"
            >
          <a class="nav-link" href="#">
            <i class="nav-icon fa fa-user"></i>
            ${profileName}
          </a>
        </li>`;
    });
    hosthtml += "</ul></li>";
    return hosthtml;
}


function loadProfileView(selectedProfile = null, selectedHost = null, callback = null)
{
    changeActiveNav(".viewProfiles")
    ajaxRequest(globalUrls.profiles.getAllProfiles, null, function(data){
        var data = $.parseJSON(data);

        let a = selectedProfile == null ? "text-info" : "";
        let hosts = `
        <li class="nav-item profile-overview">
            <a class="nav-link ${a}" href="#">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>`;


        $.each(data.clusters, (clusterIndex, cluster)=>{
            hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Cluster ${clusterIndex}</u></li>`;
            $.each(cluster.members, (_, host)=>{
                hosts = makeHostHtml(hosts, host, selectedProfile, selectedHost)
            })
        });

        hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Standalone Hosts</u></li>`;

        $.each(data.standalone.members, (_, host)=>{
            hosts = makeHostHtml(hosts, host, selectedProfile, selectedHost)
        });

        $("#sidebar-ul").empty().append(hosts);

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

    ajaxRequest(globalUrls.profiles.getProfile, {hostId: hostId, profile: profileId}, (data)=>{
        let details = $.parseJSON(data);

        let deviceTableRows = createTableRowsHtml(details.devices);

        $("#profileNameTitle").text(profileId);

        addBreadcrumbs(["Profiles", hostAlias, profileId], ["viewProfiles", "", "active"], false);

        let usedBy = ["Couldn't get profiles uesd by (api version probably)"];

        if(details.hasOwnProperty("used_by")){
            usedBy = details.used_by;
        }

        let profileUsedByHtml = "";

        $.each(usedBy, function(_, instance){
            profileUsedByHtml += `<tr><td>${instance}</td></tr>`;
        })

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
    });
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
