<div id="profileBox" class="boxSlide">
<div id="profileOverview">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h4> Profiles </h4>
                <div class="btn-group me-2">
                    <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create Profile" class="btn btn-primary" id="createProfile">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="profileCards">
        </div>
    </div>
</div>
<div id="profileDetails">
    <div class="row border-bottom">
        <div class="col-md-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
            <h4 id="profileNameTitle"> <u>
            </u></h4>
            <div class="btn-toolbar float-end">
              <div class="btn-group me-2">
                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Copy Profile" class="btn btn-primary" id="copyProfile">
                      <i class="fas fa-copy"></i>
                  </button>
                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit Profile" class="btn btn-info" id="editProfile">
                      <i class="fa fa-edit"></i>
                  </button>
                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Rename Profile" class="btn btn-warning" id="renameProfile">
                      <i class="fas fa-heading"></i>
                  </button>
                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete Profile" class="btn btn-danger" id="deleteProfile">
                      <i class="fas fa-trash"></i>
                  </button>
              </div>
            </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
<div class="col-md-4">
      <div class="card bg-dark text-white">
        <div class="card-header bg-dark" role="tab" id="profileDevicesHeading">
          <h5>
            <a id="devicesAriaToggle" class="text-white" data-bs-toggle="collapse" data-parent="#profileDevicesHeading" href="#profileDevicesCard" aria-expanded="true" aria-controls="profileDevicesCard">
              Devices
              <i class="nav-icon fa fa-cog float-end"></i>
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
    <div class="card bg-dark text-white">
      <div class="card-header bg-dark" role="tab" id="configDeviceCardHeading">
        <h5>
          <a class="text-white" data-bs-toggle="collapse" data-parent="#accordion" href="#configDeviceCard" aria-expanded="true" aria-controls="configDeviceCard">
            Configuration
            <i class="fas fa-cogs float-end"></i>
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
      <div class="card bg-dark text-white">
        <div class="card-header bg-dark" role="tab" id="usedByCard">
          <h5>
            <a data-bs-toggle="collapse" class="text-white" data-parent="#accordion" href="#usedByCollapse" aria-expanded="true" aria-controls="usedByCollapse">
              Used By
              <i class="fas fa-layer-group float-end"></i>
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
    hostId: null
};

function makeProfileSidbebarHtml(hosthtml, host, id){
    let disabled = "";
    if(host.hostOnline == false){
        disabled = "disabled text-warning text-strikethrough";
    }

    hosthtml += `<li class="mb-2">
        <a class="d-inline ${disabled}">
            <i class="fas fa-server"></i> ${host.alias}
        </a>`;

    if(host.hostOnline == true){
        hosthtml += `<button class="btn btn-outline-secondary btn-sm btn-toggle align-items-center rounded d-inline float-end me-2 toggleDropdown" data-bs-toggle="collapse" data-bs-target="#profiles-host-${id}" aria-expanded="true">
            <i class="fas fa-caret-left"></i>
        </button>`
    }else{
        return hosthtml;
    }

    hosthtml += `<div class=" mt-2 bg-dark text-white collapse" id="profiles-host-${id}">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1" style="display: inline;">`

    $.each(host.profiles, function(_, profileName){
        let active = "";

        if(host.hostId == currentProfileDetails.hostId && profileName == currentProfileDetails.profile ){
            active = "active";
        }

        hosthtml += `<li class="nav-item">
          <a class="nav-link ${active}" href="/profiles/${hostIdOrAliasForUrl(host.alias, host.hostId)}/${profileName}" data-navigo>
            <i class="nav-icon fa fa-user"></i>
            ${profileName}
          </a>
        </li>`;
    });
    hosthtml += "</ul></li>";
    return hosthtml;
}

function makeHostProfileCard(hostId, hostName, profiles)
{
    if(Object.keys(profiles).length == 0){
        return "";
    }

    let tbody = "";

    $.each(profiles, (profileName, profileValues)=>{

        let vDataIcon = profileValues.hasVendorData == 1 ? 'check text-success' : 'times';
        let userVDataIcon = profileValues.hasUserData == 1 ? 'check text-success' : 'times';

        tbody += `<tr>
            <td><a href="/profiles/${hostIdOrAliasForUrl(hostName, hostId)}/${profileName}" data-navigo>${profileName}</a></td>
            <td><i class="fas fa-${userVDataIcon}"></i></td>
            <td><i class="fas fa-${vDataIcon}"></i></td>
            <td>${profileValues.proxy}</td>
            <td>${profileValues.usb}</td>
            <td>${profileValues.disk}</td>
            <td>${profileValues.tpm}</td>
            <td>${profileValues.gpu}</td>
            <td>${profileValues.instances.length}</td>
        </tr>`;
    })

    return `<div class="card mb-2 bg-dark text-white">
        <div class="card-header">
            <h4><i class='fas fa-server me-2'></i>${hostName}</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-dark table-bordered ">
                <thead>
                    <tr>
                        <td style="max-width: 20%; width: 20%">Profile</td>
                        <td style="max-width: 10%; width: 10%">User Data</td>
                        <td style="max-width: 10%; width: 10%">Vendor Data</td>
                        <td style="max-width: 10%; width: 10%">Proxies</td>
                        <td style="max-width: 10%; width: 10%">USB's</td>
                        <td style="max-width: 10%; width: 10%">Disks</td>
                        <td style="max-width: 10%; width: 10%">TPM's</td>
                        <td style="max-width: 10%; width: 10%">GPU's</td>
                        <td style="max-width: 10%; width: 10%">Instances</td>
                    </tr>
                </thead>
                <tbody>
                ${tbody}
                </tbody>
            </table>
        </div>
    </div>`;
}

function loadProfileSidebar(){
    if($("#sidebar-ul").find("[id^=profiles]").length == 0){

        ajaxRequest(globalUrls.profiles.getAllProfiles, null, function(data){
            var data = $.parseJSON(data);

            let a = currentProfileDetails.hostId == null ? "active" : "";
            let hosts = `
            <li class="mt-2">
                <a class="nav-link p-0 ${a}" href="/profiles" data-navigo>
                    <i class="fas fa-tachometer-alt"></i> Overview
                </a>
            </li>`;

            let id = 0;
            $.each(data.clusters, (clusterIndex, cluster)=>{
                hosts += `<li class="c-sidebar-nav-title text-success pt-2"><u>Cluster ${clusterIndex}</u></li>`;
                $.each(cluster.members, (_, host)=>{
                    hosts = makeProfileSidbebarHtml(hosts, host, id)
                    id++;
                })
            });

            hosts += `<li class="c-sidebar-nav-title text-success pt-2"><u>Standalone Hosts</u></li>`;

            $.each(data.standalone.members, (_, host)=>{
                hosts = makeProfileSidbebarHtml(hosts, host, id)
                id++;
            });

            $("#sidebar-ul").empty().append(hosts);
            router.updatePageLinks()
        });
    }else{
        $("#sidebar-ul").find(".active").removeClass("active");
        if($.isNumeric(currentProfileDetails.hostId)){
            $("#sidebar-ul").find(`.nav-link[href="/profiles/${hostIdOrAliasForUrl(currentProfileDetails.hostAlias, currentProfileDetails.hostId)}/${currentProfileDetails.profile}"]`).addClass("active")
        }else{
            $("#sidebar-ul").find(".nav-link:eq(0)").addClass("active")
        }
    }
}

function loadProfileView()
{
    setBreadcrumb("Profiles", " active", "/profiles");
    $(".sidebar-fixed").addClass("sidebar-lg-show");
    changeActiveNav(".viewProfiles");
    $(".boxSlide, #profileDetails").hide();
    $("#profileOverview, #profileBox").show();
    currentProfileDetails  = {
        hostId: null,
        profile: null
    }

    loadProfileSidebar();

    $("#profileCards").empty().append(`<h4 class='text-center'><i class="fas fa-cog fa-spin"></i></h4>`)
    ajaxRequest(globalUrls.profiles.getDashboard, null, function(data){
        var data = $.parseJSON(data);
        let cards = "";

        $.each(data.clusters, (clusterIndex, cluster)=>{
            cards += `<h4 class="text-success">Cluster ${clusterIndex}</h4>`
            $.each(cluster.members, (_, host)=>{
                cards += makeHostProfileCard(host.hostId, host.alias, host.profiles);
            })
        });
        cards += `<h4 class="text-success">Standalone Hosts</h4>`
        $.each(data.standalone.members, (_, host)=>{
            cards += makeHostProfileCard(host.hostId, host.alias, host.profiles);
        });
        $("#profileCards").empty().append(cards);
        router.updatePageLinks()

    });
}

$("#sidebar-ul").on("click", ".view-profile", function(){
    viewProfile($(this).data("profile"), $(this).data("alias"), $(this).data("hostId"));
})

function createTableRowsHtml(data, childPropertyToSearch)
{
    let html = "";
    if(data.length == 0){
        html = "<tr><td colspan='2' class='text-center'><i class='fas fa-info-circle text-info me-2'></i>No Settings!</td></tr>"
    }else{
        $.each(data, function(x, y){
            if($.isPlainObject(y)){
                html += `<tr><td class='text-center' colspan='2'>${x}</td></tr>`;
                if(typeof childPropertyToSearch == "string"){
                    $.each(y[childPropertyToSearch], function(i, p){
                        html += `<tr><td>${i}</td><td>${nl2br(y)}</td></tr>`;
                    });
                }else{
                    $.each(y, function(i, p){
                        html += `<tr><td>${i}</td><td>${nl2br(p)}</td></tr>`;
                    });
                }
            }else{
                html += `<tr><td>${x}</td><td>${nl2br(y)}</td></tr>`;
            }
        });
    }
    return html;

}


function viewProfileReq(req){
    viewProfile(req.data.profile, hostsAliasesLookupTable[req.data.hostId], req.data.hostId)
}
function viewProfile(profileId, hostAlias, hostId){

    currentProfileDetails.profile = profileId;
    currentProfileDetails.hostAlias = hostAlias;
    currentProfileDetails.hostId = hostId;
    changeActiveNav(".viewProfiles");
    loadProfileSidebar()
    ajaxRequest(globalUrls.profiles.getProfile, {hostId: hostId, profile: profileId}, (data)=>{
        let details = $.parseJSON(data);

        let deviceTableRows = createTableRowsHtml(details.devices);

        $("#profileNameTitle").text(profileId);

        addBreadcrumbs(["Profiles", hostAlias, profileId], ["", "", "active"], false, ["/profiles", "", "", ""]);

        let usedBy = ["Couldn't get profiles uesd by (api version probably)"];

        if(details.hasOwnProperty("used_by")){
            usedBy = details.used_by;
        }

        let profileUsedByHtml = "";

        if(usedBy.length == 0){
            profileUsedByHtml = "<tr><td colspan='2' class='text-center'><i class='fas fa-info-circle text-info me-2'></i>Not Used!</td></tr>"
        }else{
            $.each(usedBy, function(_, instance){
                profileUsedByHtml += `<tr><td>${instance}</td></tr>`;
            })
        }


        let configTr = createTableRowsHtml(details.config);

        let collpaseDetailsFunc = $.isEmptyObject(details.devices) ? "hide" : "show";
        let collpaseConfigFunc = $.isEmptyObject(details.config) ? "hide" : "show";

        $("#profileBox #deleteProfile").attr("disabled", usedBy.length > 0);
        $("#profile-deviceData > tbody").empty().html(deviceTableRows);
        $("#profile-usedByData > tbody").empty().html(profileUsedByHtml);
        $("#profile-configData > tbody").empty().html(configTr);
        $("#profileOverview").hide();
        $(".boxSlide").hide();
        $("#profileDetails, #profileBox").show();
        router.updatePageLinks()
    });
}


$("#profileBox").on("click", "#createProfile", function(){
    $("#modal-profile-create").modal("show");
});

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

$("#profileBox").on("click", "#editProfile", function(){
    editProfileData.hostAlias = currentProfileDetails.hostAlias;
    editProfileData.hostId = currentProfileDetails.hostId;
    editProfileData.currentName = currentProfileDetails.profile;
    $("#modal-profile-edit").modal("show");
});

$("#profileBox").on("click", "#deleteProfile", function(){
    let sidebarItem =$("#sidebar-ul").find(`.nav-link[href="/profiles/${hostIdOrAliasForUrl(currentProfileDetails.hostAlias, currentProfileDetails.hostId)}/${currentProfileDetails.profile}"]`);
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
                            sidebarItem.remove();
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
require __DIR__ . "/../modals/profiles/replace.php";
require __DIR__ . "/../modals/profiles/rename.php";
require __DIR__ . "/../modals/profiles/copy.php";
require __DIR__ . "/../modals/profiles/create.php";
?>
