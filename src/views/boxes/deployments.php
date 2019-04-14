<div id="deploymentsBox" class="boxSlide">
<div id="deploymentsOverview" class="row">
    <div class="col-md-9">
          <div class="card">
            <div class="card-header" role="tab" id="headingOne">
              <h5>
                <a data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                  Deployments
                </a>
              </h5>
            </div>
            <div id="cloudConfigDescription" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
              <div class="card-block">
                  Deployments are used to deploy multiple cloud configs to multiple
                  containers.
              </div>
            </div>
          </div>
    </div>
    <div class="col-md-3">
          <div class="card">
            <div class="card-header" role="tab" id="headingOne">
              <h5>
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Actions
                </a>
              </h5>
            </div>
            <div id="collapseOne" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
              <div class="card-block">
                  <button class="btn btn-block btn-primary" id="createDeployment">
                      Create
                  </button>
              </div>
            </div>
          </div>
    </div>
</div>
<div id="depoymentDetails">

</div>
</div>

<script>

function loadDeploymentsView()
{
    $(".boxSlide, #projectDetails").hide();
    $("#deploymentsOverview, #deploymentsBox").show();

    ajaxRequest(globalUrls.deployments.getAll, {}, (data)=>{
        data = $.parseJSON(data);

        var treeData = [{
            text: "Overview",
            icon: "fa fa-home",
            type: "projectsOverview",
            state: {
                selected: true
            }
        }];

        $.each(data, function(i, item){
            treeData.push({
                text: item.name,
                icon: "fas fa-archive",
                type: "deployment",
            });
        });


        $('#jsTreeSidebar').treeview({
            data: treeData,
            levels: 5,
            onNodeSelected: function(event, node) {
                if(node.type == "project"){
                    viewProject(node.id, node.hostId);
                } else if (node.type == "projectsOverview"){
                    loadDeploymentsView();
                }
            }
        });
    });
}

$("#deploymentsBox").on("click", "#createDeployment", function(){
    $("#modal-deployments-create").modal("show");
});

</script>

<?php
    require __DIR__ . "/../modals/deployments/createDeployment.php";
?>
