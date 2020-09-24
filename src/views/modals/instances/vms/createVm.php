<!-- Modal -->
<div class="modal fade" id="modal-vms-create" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title">Create VM</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
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
          <label>  </label>
          <label
              data-toggle="tooltip"
              data-placement="top"
              title="Currently an image needs to have been imported into atleast
              one server on the network to use it here! Images will be downloaded
              onto hosts that dont have the selected image.">
              Image
              <i class="fas fa-question-circle"></i>
          </label>
          <input id="newVirtualMachineImage" type="text" class="form-control"/>
      </div>
      <div class="form-group">
          <b> Hosts </b>
          <input class="form-control" name="hosts"  id="newVmHosts"/>
      </div>
      <div class="alert alert-info">
          Default password ubuntu. <b> You should wait 30~ seconds before attempting to access
          the console </b> as cloud-init has to install the lxd-agent and reboot
          the vm.

          <b> Password ssh is not enabled by default </b>
      </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" id="createVm">Create</button>
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
    $("#newVmHosts").tokenInput("clear");
});

$("#modal-vms-create").on("click", "#createVm", function(){
    let hosts = mapObjToSignleDimension($("#newVmHosts").tokenInput("get"), "hostId");

    let usernameInput = $("#modal-vms-create input[name=username]");
    let username = usernameInput.val();

    let nameInput = $("#modal-vms-create input[name=name]");
    let name = nameInput.val();

    let image = $("#newVirtualMachineImage").tokenInput("get");

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

    let x = {
        hostIds: hosts,
        username: username,
        imageDetails: image[0]["details"],
        name: name
    }

    ajaxRequest(globalUrls.instances.virtualMachines.create, x, (data)=>{
        data = makeToastr(data);
        if(data.state == "success"){
            $("#modal-vms-create").modal("hide");
        }
    });
});

</script>
