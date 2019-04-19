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
          <div class="form-group">
              <b> Cloud Configs </b>
              <input class="form-control" id="newDeploymentConfigs"/>
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

$("#newDeploymentConfigs").tokenInput(globalUrls.cloudConfig.search.searchAll, {
    queryParam: "criteria",
    propertyToSearch: "name",
    theme: "facebook",
    tokenValue: "id",
});

$("#modal-deployments-create").on("click", "#create", function(){

    let cloudConfigs = mapObjToSignleDimension($("#newDeploymentConfigs").tokenInput("get"), "id");

    let x = {
        name: $("#modal-deployments-create #newDeploymentName").val(),
        cloudConfigs: cloudConfigs
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
