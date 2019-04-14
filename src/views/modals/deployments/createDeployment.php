    <!-- Modal -->
<div class="modal fade" id="modal-deployments-create" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Deployment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <b> Name </b>
              <input class="form-control" id="newDeploymentName"/>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="create">Create</button>
      </div>
    </div>
  </div>
</div>
<script>

$("#modal-deployments-create").on("click", "#create", function(){
    let x = {
        name: $("#modal-deployments-create #newDeploymentName").val()
    };

    ajaxRequest(globalUrls.deployments.create, x, function(data){
        data = makeToastr(data);
        if(data.state == "error"){
            return false;
        }
        $("#modal-deployments-create").modal("toggle");
    });
})
</script>
