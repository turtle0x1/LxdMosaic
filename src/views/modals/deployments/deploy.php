<!-- Modal -->
<div class="modal fade" id="modal-deployments-deploy" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Deploy Deployment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <table class="table table-bordered" id="deployCloudConfigTable">
              <thead>
                  <tr>
                      <th> Cloud Config </th>
                      <th> Number Of Instances </th>
                  </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="deploy">Deploy</button>
      </div>
    </div>
  </div>
</div>
<script>

var deploymentDeployObj = {
    deploymentId: null
}

$("#modal-deployments-deploy").on("shown.bs.modal", function(){
    ajaxRequest(globalUrls.deployments.getDeploymentConfigs, deploymentDeployObj, function(data){
        data = $.parseJSON(data);
        let trs = "";
        $.each(data, function(i, item){
            trs += `<tr id="${item.revId}">
                <td>${item.name}</td>
                <td><input name="qty" class="form-control"/></td>
            </tr>`;
        });
        $("#deployCloudConfigTable > tbody").empty().append(trs);
    })
});

$("#modal-deployments-deploy").on("click", "#deploy", function(){
    let x = [];
    $("#deployCloudConfigTable > tbody > tr").each(function(){
        x.push({
            revId: $(this).attr("id"),
            qty: $(this).find("input [name=qty]").val()
        });
    });

    let p = $.extend({
        instances: x
    }, deploymentDeployObj);
    console.log(p);
    ajaxRequest(globalUrls.deployments.deploy, p, function(data){
        console.log(data);
    })
});
</script>
