    <!-- Modal -->
<div class="modal fade" id="modal-container-assignProfiles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Assign Profiles</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <div>
                  <label class="div-inline">Profiles</label>
                  <div class="text-info d-inline">
                      <i class="fas fa-info-circle mr-1"></i>Only searches profiles on this instances server!
                  </div>
              </div>

              <input class="form-control" id="newProfilesListInput"/>
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="assignProfiles" class="btn btn-primary">Assign Profiles</button>
      </div>
    </div>
  </div>
</div>
<script>

    var snapshotDetails = {
        snapshotName: null
    };

    $("#newProfilesListInput").tokenInput(globalUrls.profiles.search.searchHostProfiles, {
        queryParam: "search",
        propertyToSearch: "name",
        tokenValue: "name",
        tokenLimit: 100,
        preventDuplicates: true,
        theme: "facebook",
        setExtraSearchParams: function(){
            return {hostId: currentContainerDetails.hostId};
        }
    });

    $("#modal-container-assignProfiles").on("hide.bs.modal", function(){
        $("#newProfilesListInput").tokenInput("clear");
    });

    $("#modal-container-assignProfiles").on("click", "#assignProfiles", function(){
        let profiles = mapObjToSignleDimension($("#newProfilesListInput").tokenInput("get"), "name");

        if(profiles.length == 0){
            $.alert("Please select atleaset one profile");
            return false;
        }
        let x = {...{profiles: profiles}, ...currentContainerDetails};
        ajaxRequest(globalUrls.instances.profiles.assign, x, (response)=>{
            response = makeToastr(response);
            if(response.state == "error"){
                return false;
            }
            $("#modal-container-assignProfiles").modal("hide");
            loadContainerViewAfter();
        });
    });

    $("#modal-container-assignProfiles").on("show.bs.modal", function(){
        if(!$.isPlainObject(currentContainerDetails)){
            $("#modal-container-migrate").modal("toggle");
            alert("required variable isn't right");
            return false;
        }else if(typeof currentContainerDetails.container !== "string"){
            $("#modal-container-migrate").modal("toggle");
            alert("container isn't set");
            return false;
        }
    });
</script>
