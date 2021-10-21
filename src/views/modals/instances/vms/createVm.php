<!-- Modal -->
<div class="modal fade" id="modal-vms-create" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title">Create VM</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body" style="min-height: 60vh; max-height: 60vh;">
      <div class="row">
          <div class="col-md-3">
              <ul class="list-group" id="createVmStepList">
                  <li style="cursor: pointer" class="list-group-item active">1. Basic Details</li>
                  <li style="cursor: pointer" class="list-group-item">2. Config (Optional)</li>
              </ul>
          </div>
          <div class="col-md-9" style="max-height: 57vh; min-height: 57vh; overflow-y: scroll; border-left: 1px solid black;">
              <div class="createVmBox" data-step="1" id="">
                  <div class="mb-2">
                      <b> Name </b>
                      <input class="form-control" name="name" />
                  </div>
                  <div class="mb-2">
                      <b> Username </b>
                      <input class="form-control" name="username" />
                  </div>
                  <div class="mb-2">
                      <label
                          data-toggle="tooltip"
                          data-bs-placement="top"
                          title="Currently an image needs to have been imported into atleast
                          one server on the network to use it here! Images will be downloaded
                          onto hosts that dont have the selected image.">
                          <b>Image</b>
                          <i class="fas fa-question-circle"></i>
                      </label>
                      <input id="newVirtualMachineImage" type="text" class="form-control"/>
                  </div>
                  <div class="mb-2">
                      <b> Hosts </b>
                      <input class="form-control" name="hosts"  id="newVmHosts"/>
                  </div>
                  <div class="mb-2">
                      <b> Memory Limit (1GB Default) </b>
                      <input class="form-control" name="memoryLimit" value="1GB" />
                  </div>
                  <div class="">
                      <div class="mb-2">
                          <i class="fas fa-info-circle text-info me-2"></i>Your account password will be set to <code>ubuntu</code>
                      </div>
                      <div class="mb-2">
                          <i class="fas fa-info-circle text-info me-2"></i>You should wait 30~ seconds after first boot before attempting to access the console
                      </div>
                      <div class="">
                          <i class="fas fa-info-circle text-info me-2"></i>Password ssh is not enabled by default
                      </div>
                  </div>
              </div>
              <div class="createVmBox" data-step="2" id="" style="display: none;">
                  <label for="newVmSettings">
                      Settings(Optional)
                      <button id="addVmSetting" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i></button>
                  </label>
                  <table class="table table-borered" id="newVmSettings">
                      <thead>
                          <tr>
                              <th> Setting </th>
                              <th> Value </th>
                              <th> Remove </th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="button" class="btn btn-primary createVirtualMachine" data-start="0">Create</button>
    <button type="button" class="btn btn-success createVirtualMachine" data-start="1">Create & Start</button>
  </div>
</div>
</div>
</div>
<script>

var vmSettingRow = "";

$("#newVirtualMachineImage").tokenInput(globalUrls.images.search.searchAllHosts, {
    queryParam: "search",
    tokenLimit: 1,
    propertyToSearch: "description",
    theme: "facebook",
    tokenValue: "details",
    setExtraSearchParams: ()=>{
        return {type: "virtual-machine"};
    }
});

$("#newVmHosts").tokenInput(globalUrls.hosts.search.search, {
    queryParam: "hostSearch",
    propertyToSearch: "host",
    tokenValue: "hostId",
    preventDuplicates: false,
    theme: "facebook"
});

$("#modal-vms-create").on("show.bs.modal", function(){
    ajaxRequest(globalUrls.instances.settings.getAllAvailableSettings, {}, function(data){
        data = $.parseJSON(data);
        let selectHtml = "<select name='key' class='form-control containerSetting'><option value=''>Please Select</option>";
        $.each(data, function(i, item){
            selectHtml += `<option value='${item.key}' data-value="${item.value}">${item.key}</option>`;
        });
        selectHtml += `</select>`;
        vmSettingRow = `<tr>
            <td>${selectHtml}</td>
            <td><input name="value" class='form-control'/></td>
            <td><button class="removeSetting btn btn-danger"><i class='fas fa-trash'></i></button></td>
            </tr>`

    });
});

$("#modal-vms-create").on("hide.bs.modal", function(){
    $("#modal-vms-create input").val("");
    $("#modal-vms-create input[name=memoryLimit]").val("1GB");
    $("#newVmHosts").tokenInput("clear");
    $("#newVirtualMachineImage").tokenInput("clear");
    $("#newVmSettings > tbody").empty()
});

$("#modal-vms-create").on("click", "#createVmStepList li", function(){
    changeCreateVmBox($(this).index() + 1);
});

$("#modal-vms-create").on("click", ".removeSetting", function(){
    $(this).parents("tr").remove();
});

$("#modal-vms-create").on("click", "#addVmSetting", function(){
    $("#newVmSettings > tbody").append(vmSettingRow);
});

function changeCreateVmBox(newIndex){
    $(".createVmBox").hide();
    $(`.createVmBox[data-step='${(newIndex)}']`).show();
    $("#createVmStepList").find(".active").removeClass("active");
    $("#createVmStepList").find(`li:eq(${newIndex - 1})`).addClass("active");
}

$("#modal-vms-create").on("click", ".createVirtualMachine", function(){
    let hosts = mapObjToSignleDimension($("#newVmHosts").tokenInput("get"), "hostId");

    let usernameInput = $("#modal-vms-create input[name=username]");
    let username = usernameInput.val();

    let nameInput = $("#modal-vms-create input[name=name]");
    let name = nameInput.val();

    let image = $("#newVirtualMachineImage").tokenInput("get");

    let btn = $(this);

    let startVm = parseInt(btn.data("start"));

    let defaultBtnText = "Create";

    if(startVm == 1){
        defaultBtnText = "Create & Start";
    }

    if(image.length == 0 || !image[0].hasOwnProperty("details")){
        changeCreateVmBox(1)
        makeToastr(JSON.stringify({state: "error", message: "Please select image"}));
        return false;
    }

    if(name == ""){
        changeCreateVmBox(1)
        nameInput.focus();
        makeToastr(JSON.stringify({state: "error", message: "Please input vm name"}));
        return false;
    }else if(username == ""){
        changeCreateVmBox(1)
        usernameInput.focus();
        makeToastr(JSON.stringify({state: "error", message: "Please input username"}));
        return false;
    } else if(hosts.length == 0){
        changeCreateVmBox(1)
        $("#newNetworkHosts").focus();
        makeToastr(JSON.stringify({state: "error", message: "Please select atleast one host"}));
        return false;
    }

    let memoryLimitInput = $("#modal-vms-create input[name=memoryLimit]");
    let memoryLimit = memoryLimitInput.val();

    btn.html('<i class="fa fa-cog fa-spin"></i>Creating..');
    $("#modal-vms-create").find(".btn").attr("disabled", true);

    let config = {};
    let invalid = false;
    let message = "";
    let failedInput;
    $("#newVmSettings > tbody > tr").each(function(){
        let keyInput = $(this).find("select[name=key]");
        let valueInput = $(this).find("input[name=value]");
        let key = keyInput.val();
        let value = valueInput.val();
        if(key == ""){
            failedInput = keyInput
            invalid = true;
            message = "Please select setting";
            return false;
        }else if(value == ""){
            failedInput = valueInput
            invalid = true;
            message = "Please select value";
            return false;
        }

        config[key] = value;
    });

    if(invalid){
        changeCreateVmBox(2)
        $("#modal-vms-create").find(".btn").attr("disabled", false);
        btn.html(defaultBtnText);
        failedInput.focus()
        makeToastr(JSON.stringify({state: "error", message: message + " or delete row"}));
        return false;
    }

    config["limits.memory"] = memoryLimit

    let x = {
        hostIds: hosts,
        username: username,
        imageDetails: image[0]["details"],
        name: name,
        start: startVm,
        config: config
    }

    ajaxRequest(globalUrls.instances.virtualMachines.create, x, (data)=>{
        data = makeToastr(data);
        $("#modal-vms-create").find(".btn").attr("disabled", false);
        if(data.state == "success"){
            $("#modal-vms-create").modal("hide");
        }
        btn.html(defaultBtnText);
    });
});

</script>
