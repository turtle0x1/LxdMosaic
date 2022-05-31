    <!-- Modal -->
<div class="modal fade" id="modal-profile-create" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-user me-2"></i>Create Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="min-height: 60vh; max-height: 60vh;">
          <div class="row">
              <div class="col-md-3">
                  <ul class="list-group" id="createProfileStepList">
                      <li style="cursor: pointer" class="list-group-item active">1. Basic Details</li>
                      <li style="cursor: pointer" class="list-group-item">2. Config (Optional)</li>
                      <li style="cursor: pointer" class="list-group-item">3. Devices (Optional)</li>
                  </ul>
              </div>
              <div class="col-md-9" style="max-height: 57vh; min-height: 57vh; overflow-y: scroll; border-left: 1px solid black;">
                  <div class="createProfileBox" data-step="1">
                      <div class="mb-2">
                          <b> Hosts </b>
                          <input class="form-control" id="profileCreateTargets"/>
                      </div>
                      <div class="mb-2">
                          <b> Profile Name </b>
                          <input class="form-control" id="createProfileName"/>
                      </div>
                      <div class="mb-2">
                          <b> Description (Optional) </b>
                          <textarea class="form-control" id="profileDescription"></textarea>
                      </div>
                  </div>
                  <div class="createProfileBox pt-2" data-step="2" style="display: none">
                      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                          <b>Profile Config (Optional)</b>
                          <button class="btn btn-outline-primary btn-sm" id="addProfileSettingRow">
                              <i class="fas fa-plus"></i>
                          </button>
                      </div>
                      <table class="table table-bordered" id="profileSettingTable">
                          <thead>
                              <tr>
                                  <th>Key</th>
                                  <th>Value</th>
                                  <th></th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                  </div>
                  <div class="createProfileBox pt-2" data-step="3" style="display: none">
                      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                          <b>Profile Devices (Optional)</b>
                          <button class="btn btn-outline-primary btn-sm" id="addProfileDevice">
                              <i class="fas fa-plus"></i>
                          </button>
                      </div>
                      <div class="list-group" id="newProfileDeviceObjects">
                      </div>
                  </div>

             </div>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="createProfileBtn">Create</button>
      </div>
    </div>
  </div>
</div>
<script>
$("#profileCreateTargets").tokenInput(globalUrls.hosts.search.search, {
    queryParam: "hostSearch",
    propertyToSearch: "host",
    tokenValue: "hostId",
    preventDuplicates: false,
    theme: "facebook"
});

var profilesTableRow = "";

$("#modal-profile-create").on("hide.bs.modal",  function(){
    $("#modal-profile-create input").val("");
    $("#profileCopyTargets").tokenInput("clear");
    $("#newProfileDeviceObjects").empty()
});

$("#modal-profile-create").on("hide.bs.modal", function(){
    $("#profileSettingTable > tbody").empty()
    $("#profileCreateTargets").tokenInput("clear");
    $("#createProfileName").val("");
    $("#profileDescription").val("");
    changeCreateProfileBox(1)
});

$("#modal-profile-create").on("click", ".removeNewDevice", function(){
    $(this).parents(".list-group-item").remove()
});
$("#modal-profile-create").on("click", "#addProfileDevice", function(){
    newDeviceHelperObj.callback = function(device){
        let html = `<div href="#" class="list-group-item list-group-item-action text-dark" aria-current="true" data-device='${JSON.stringify(device)}'>
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1">${device.name}</h5>
          <button class="btn btn-sm btn-outline-danger removeNewDevice">
            <i class="fas fa-trash"></i>
          </button>
        </div>
        <p class="mb-1">${device.type}</p>`

        $.each(device.properties, (key, value)=>{
            html += `<div><small><b>${key}:</b> ${value}</small></div>`
        })

        html += `</div>`
        $("#newProfileDeviceObjects").append(html)
    }
    $("#modal-helpers-newDeviceObj").modal("show")
})

$("#modal-profile-create").on("click", "#createProfileStepList li", function(){
    changeCreateProfileBox($(this).index() + 1);
});

function changeCreateProfileBox(newIndex){
    $(".createProfileBox").hide();
    $(`.createProfileBox[data-step='${(newIndex)}']`).show();
    $("#createProfileStepList").find(".active").removeClass("active");
    $("#createProfileStepList").find(`li:eq(${newIndex - 1})`).addClass("active");
}

$("#modal-profile-create").on("shown.bs.modal", function(){
    let options = "";
    $.each(lxdProfileKeys, (_, item)=>{
        options += `<option value='${item}'>${item}</option>`
    });
    profilesTableRow = `<tr>
        <td><select class='form-select' name="key">${options}</select></td>
        <td><input class='form-control' name="value"/></td>
        <td><button class='btn btn-outline-danger deleteProfileSettingRow'><i class="fas fa-trash"></i></button></td>
    </tr>`
});

$("#modal-profile-create").on("click", ".deleteProfileSettingRow", function(){
    $(this).parents("tr").remove();
});

$("#modal-profile-create").on("click", "#addProfileSettingRow", function(){
    $("#profileSettingTable > tbody").append(profilesTableRow);
});

$("#modal-profile-create").on("click", "#createProfileBtn", function(){
    let hosts = mapObjToSignleDimension($("#profileCreateTargets").tokenInput("get"), "hostId");

    let nameInput = $("#createProfileName");
    let name = nameInput.val();
    let description = $("#profileDescription").val();

    if (hosts.length == 0) {
        $("#profileCreateTargets").focus();
        makeToastr(JSON.stringify({state: "error", message: "Please hosts to create on"}));
        changeCreateProfileBox(1)
        return false;
    }else if(name == ""){
        changeCreateProfileBox(1)
        nameInput.focus();
        makeToastr(JSON.stringify({state: "error", message: "Please provide new profile name"}));
        return false;
    }

    let invalid = false;
    let config = {};

    $("#profileSettingTable > tbody > tr").each(function(){
        let tr = $(this);
        let keyInput = tr.find("select[name=key]");
        let key = keyInput.val().trim();
        let valueInput = tr.find("input[name=value]")
        let value = valueInput.val().trim();

        if(key == ""){
            keyInput.focus()
            invalid = true;
            return false;
        }else if(value == ""){
            valueInput.focus()
            invalid = true;
            return false;
        }

        config[key] = value;
    });

    if(invalid){
        makeToastr(JSON.stringify({state: "error", message: "Check profile config values"}));
        changeCreateProfileBox(2)
        return false;
    }

    let devices = {}

    $("#newProfileDeviceObjects .list-group-item").each(function(){
        let device = $(this).data().device

        devices[device.name] = {
            type: device.type,
            ...device.properties
        }
    });

    let x = {hosts: hosts, name: name, description: description, config: config, devices: devices};

    ajaxRequest(globalUrls.profiles.create, x, (data)=>{
        data = makeToastr(data);
        if(data.state == "success"){
            $("#modal-profile-create").modal("toggle");
            loadProfileView();
        }
    });
});

</script>
