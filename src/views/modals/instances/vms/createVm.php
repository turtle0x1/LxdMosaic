<!-- Modal -->
<div class="modal fade" id="modal-vms-create" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title">Create VM</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
      <div class="form-group">
          <b> Name </b>
          <input class="form-control" name="name" />
      </div>
      <div class="form-group">
          <b> Username </b>
          <input class="form-control" name="username" />
      </div>
      <div class="form-group">
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
      <div class="form-group">
          <b> Hosts </b>
          <input class="form-control" name="hosts"  id="newVmHosts"/>
      </div>
      <div class="form-group">
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
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="button" class="btn btn-primary createVirtualMachine" data-start="0">Create</button>
    <button type="button" class="btn btn-success createVirtualMachine" data-start="1">Create & Start</button>
  </div>
</div>
</div>
</div>
<script>

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

$("#modal-vms-create").on("hide.bs.modal", function(){
    $("#modal-vms-create input").val("");
    $("#modal-vms-create input[name=memoryLimit]").val("1GB");
    $("#newVmHosts").tokenInput("clear");
    $("#newVirtualMachineImage").tokenInput("clear");
});

$("#modal-vms-create").on("click", ".createVirtualMachine", function(){
    let hosts = mapObjToSignleDimension($("#newVmHosts").tokenInput("get"), "hostId");

    let usernameInput = $("#modal-vms-create input[name=username]");
    let username = usernameInput.val();

    let nameInput = $("#modal-vms-create input[name=name]");
    let name = nameInput.val();

    let image = $("#newVirtualMachineImage").tokenInput("get");

    let btn = $(this);


    if(image.length == 0 || !image[0].hasOwnProperty("details")){
        // btn.html('Create Container');
        // $("#modal-container-create").find(".btn").attr("disabled", false);
        makeToastr(JSON.stringify({state: "error", message: "Please select image"}));
        return false;
    }

    if(name == ""){
        nameInput.focus();
        makeToastr(JSON.stringify({state: "error", message: "Please input vm name"}));
        return false;
    }else if(username == ""){
        usernameInput.focus();
        makeToastr(JSON.stringify({state: "error", message: "Please input username"}));
        return false;
    } else if(hosts.length == 0){
        $("#newNetworkHosts").focus();
        makeToastr(JSON.stringify({state: "error", message: "Please select atleast one host"}));
        return false;
    }

    let memoryLimitInput = $("#modal-vms-create input[name=memoryLimit]");
    let memoryLimit = memoryLimitInput.val();

    btn.html('<i class="fa fa-cog fa-spin"></i>Creating..');
    $("#modal-vms-create").find(".btn").attr("disabled", true);

    let x = {
        hostIds: hosts,
        username: username,
        imageDetails: image[0]["details"],
        name: name,
        start: parseInt(btn.data("start")),
        memoryLimit: memoryLimit
    }

    let defaultBtnText = "Create";

    if(x.start == 1){
        defaultBtnText = "Create & Start";
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
