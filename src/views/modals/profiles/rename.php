    <!-- Modal -->
<div class="modal fade" id="modal-profile-rename" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Rename Profile <b><span class="renameProfile-profileName"></span></b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <b> Host: </b> <span id="renameProfile-host"></span>
          <div class="form-group">
              <b> New Profile Name </b>
              <input class="form-control" id="newProfileName"/>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="renameProfile">Rename</button>
      </div>
    </div>
  </div>
</div>
<script>
    var renameProfileData = {
        host: null,
        currentName:  null,
        hostId: null
    }

    $("#modal-profile-rename").on("shown.bs.modal", function(){
        if(renameProfileData.host == null){
            makeToastr(JSON.stringify({state: "error", message: "Developer fail - Please provide host"}));
            return false;
        } else if(renameProfileData.currentName == null){
            makeToastr(JSON.stringify({state: "error", message: "Developer fail - Please provide currentName"}));
            return false;
        }

        $(".renameProfile-profileName").text(renameProfileData.currentName);
        $("#renameProfile-host").text(renameProfileData.host);
    });

    $("#modal-profile-rename").on("click", "#renameProfile", function(){
        let newProfileNameInput = $("#modal-profile-rename #newProfileName");
        let newProfileNameVal = newProfileNameInput.val();

        if(newProfileNameVal == ""){
            makeToastr(JSON.stringify({state: "error", message: "Please provide new profile name"}));
            newProfileNameInput.focus();
            return false;
        }

        let x = $.extend({
            newProfileName: newProfileNameVal
        }, renameProfileData);
        ajaxRequest(globalUrls.profiles.rename, x, (data)=>{
            data = makeToastr(data);
            if(data.state == "success"){
                $("#modal-profile-rename").modal("toggle");
                loadProfileView();
            }
        });
    });

</script>
