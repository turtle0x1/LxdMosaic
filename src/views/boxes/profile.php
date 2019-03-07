<div id="profileBox" class="boxSlide">
<div id="profileOverview" class="row">
    <div class="col-md-9">
          <div class="card">
            <div class="card-header" role="tab" id="headingOne">
              <h5>
                <a data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                  Profiles
                </a>
              </h5>
            </div>
            <div id="cloudConfigDescription" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
              <div class="card-block">
                  Profiles are used to store processes used with a container.
              </div>
            </div>
          </div>
    </div>
</div>
<div id="profileDetails" class="row">
<!-- <h4> Container: <`span id="containerName"></span> </h4> -->
<div class="col-md-3">
      <div class="card">
        <div class="card-header" role="tab" id="profilesActionHeading">
          <h5>
            <a data-toggle="collapse" data-parent="#accordion" href="#profileActions" aria-expanded="true" aria-controls="profileActions">
              Actions
            </a>
          </h5>
        </div>
        <div id="profileActions" class="collapse show" role="tabpanel" aria-labelledby="profilesActionHeading">
          <div class="card-block table-responsive">
              <div id="collapseOne" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
                <div class="card-block">
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
        <div class="card-header" role="tab" id="profileDevicesHeading">
          <h5>
            <a id="devicesAriaToggle" data-toggle="collapse" data-parent="#profileDevicesHeading" href="#profileDevicesCard" aria-expanded="true" aria-controls="profileDevicesCard">
              Devices
            </a>
          </h5>
        </div>
        <div id="profileDevicesCard" class="collapse show" role="tabpanel" aria-labelledby="profileDevicesHeading">
          <div class="card-block table-responsive">
              <table class="table table-striped" id="profile-deviceData">
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
        <div class="card-header" role="tab" id="configDeviceCardHeading">
          <h5>
            <a data-toggle="collapse" data-parent="#accordion" href="#configDeviceCard" aria-expanded="true" aria-controls="configDeviceCard">
              Config Data
            </a>
          </h5>
        </div>

        <div id="configDeviceCard" class="collapse show" role="tabpanel" aria-labelledby="configDeviceCardHeading">
          <div class="card-block table-responsive">
              <table class="table table-striped" id="profile-configData">
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
        <div class="card-header" role="tab" id="usedByCard">
          <h5>
            <a data-toggle="collapse" data-parent="#accordion" href="#usedByCollapse" aria-expanded="true" aria-controls="usedByCollapse">
              Entities Profile Used By
            </a>
          </h5>
        </div>

        <div id="usedByCollapse" class="collapse show" role="tabpanel" aria-labelledby="usedByCard">
          <div class="card-block">
              <table class="table table-responsive table-striped" id="profile-usedByData">
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
    ajaxRequest(globalUrls["profiles"].getAllProfiles, null, function(data){
        var data = $.parseJSON(data);
        var treeData = [{
            text: "Overview",
            icon: "fa fa-home",
            type: "profileOverview",
            state: {
                selected: true
            }
        }];
        let matched = false;
        $.each(data, function(hostName, host){
            let profiles = [];
            $.each(host.profiles, function(profileName, details){
                let x = {
                    text: profileName,
                    icon: "fa fa-user",
                    type: "profile",
                    id: profileName,
                    host: hostName
                };
                if(hostName == selectedHost && profileName == selectedProfile ){
                    matched = true;
                    x.state = {
                        selected: true
                    };
                }
                profiles.push(x);
            });
            treeData.push({
                text: hostName,
                nodes: profiles,
                icon: "fa fa-server"
            })
        });
        $(".boxSlide, #profileDetails").hide();
        $("#profileOverview, #profileBox").show();
        if(matched){
            treeData[0].state.selected = false;
        }
        $('#jsTreeSidebar').treeview({
            data: treeData,
            levels: 5,
            onNodeSelected: function(event, node) {
                if(node.type == "profile"){
                    viewProfile(node.id, node.host);
                } else if (node.type == "profileOverview"){
                    $(".boxSlide, #profileDetails").hide();
                    $("#profileOverview, #profileBox").show();
                }
            }
        });
        profileData = data;
        if($.isFunction(callback)){
            callback();
        }
    });
}

function viewProfile(profileId, host){
    currentProfileDetails.profile = profileId;
    currentProfileDetails.host =    host;
    let details = profileData[currentProfileDetails.host]["profiles"][currentProfileDetails.profile]["details"];
    let deviceTableRows = createTableRowsHtml(details.devices);

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


$("#profileBox").on("click", "#renameProfile", function(){

    renameProfileData.host = currentProfileDetails.host;
    renameProfileData.currentName = currentProfileDetails.profile;
    $("#modal-profile-rename").modal("show");
});

$("#profileBox").on("click", "#deleteProfile", function(){
    $.confirm({
        title: 'Delete profile ' + currentProfileDetails.profile + ' from ' + currentProfileDetails.host,
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
?>
