    <!-- Modal -->
<div class="modal fade" id="modal-profile-create" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">
            Create Profile <b>
                <span id="hostAlias"></span>
            </b>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <b> Hosts </b>
              <input class="form-control" id="profileCreateTargets"/>
          </div>
          <div class="form-group">
              <b> Profile Name </b>
              <input class="form-control" id="createProfileName"/>
          </div>
          <div class="form-group">
              <b> Description (Optional) </b>
              <textarea class="form-control" id="profileDescription"></textarea>
          </div>
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
              <b>Profile Config (Optional)</b>
              <button class="btn btn-outline-primary btn-sm" id="addProfileSettingRow">
                  <i class="fas fa-plus"></i>
              </button>
          </div>
          <div class="mt-2">
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
});

$("#modal-profile-create").on("hide.bs.modal", function(){
    $("#profileSettingTable > tbody").empty()
    $("#profileCreateTargets").tokenInput("clear");
    $("#createProfileName").val("");
    $("#profileDescription").val("");
});

$("#modal-profile-create").on("shown.bs.modal", function(){
    let options = "";
    $.each(lxdProfileKeys, (_, item)=>{
        options += `<option value='${item}'>${item}</option>`
    });
    profilesTableRow = `<tr>
        <td><select class='form-control' name="key">${options}</select></td>
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
        return false;
    }else if(name == ""){
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
        return false;
    }

    let x = {hosts: hosts, name: name, description: description, config: config};

    ajaxRequest(globalUrls.profiles.create, x, (data)=>{
        data = makeToastr(data);
        if(data.state == "success"){
            $("#modal-profile-create").modal("toggle");
            loadProfileView();
        }
    });
});

</script>
