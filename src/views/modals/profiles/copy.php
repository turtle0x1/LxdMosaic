    <!-- Modal -->
<div class="modal fade" id="modal-profile-copy" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">
            Copy Profile <b>
                <span id="hostAlias"></span>
                <span id="profileName"></span>
            </b>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <b> Select Hosts To Copy To </b>
              <input class="form-control" id="profileCopyTargets"/>
          </div>
          <div class="form-group">
              <b> New Profile Name </b>
              <input class="form-control" id="profileCopyNewName"/>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="copyProfileBtn">Copy</button>
      </div>
    </div>
  </div>
</div>
<script>
var copyProfileData = {
    hostId: null,
    hostAlias: null,
    profile: null
};

$("#profileCopyTargets").tokenInput(globalUrls.hosts.search.search, {
    queryParam: "host",
    propertyToSearch: "host",
    tokenValue: "hostId",
    preventDuplicates: false,
    theme: "facebook"
});

$("#modal-profile-copy").on("hide.bs.modal",  function(){
    $("#modal-profile-copy input").val("");
    $("#profileCopyTargets").tokenInput("clear");
});

$("#modal-profile-copy").on("shown.bs.modal", function(){
    $("#modal-profile-copy #hostAlias").text(copyProfileData.hostAlias);
    $("#modal-profile-copy #profileName").text(copyProfileData.profile);
});

$("#modal-profile-copy").on("click", "#copyProfileBtn", function(){
    let targetHosts = mapObjToSignleDimension($("#profileCopyTargets").tokenInput("get"), "hostId");

    let newNameInput = $("#profileCopyNewName");
    let newName = newNameInput.val();

    if (targetHosts.length == 0) {
        $("#profileCopyTargets").focus();
        makeToastr(JSON.stringify({state: "error", message: "Please hosts to copy to"}));
        return false;
    }else if(newName == ""){
        newNameInput.focus();
        makeToastr(JSON.stringify({state: "error", message: "Please provide new profile name"}));
        return false;
    }

    let x = $.extend({
        targetHosts: targetHosts,
        newName: newName
    }, copyProfileData);

    ajaxRequest(globalUrls.profiles.copy, x, (data)=>{
        data = makeToastr(data);
        if(data.state == "success"){
            $("#modal-profile-copy").modal("toggle");
            loadProfileView();
        }
    });
});

</script>
