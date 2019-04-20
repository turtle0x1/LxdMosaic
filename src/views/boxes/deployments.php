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
    <div id="depoymentDetails" class="row">
        <div class="col-md-3">
              <div class="card">
                <div class="card-header" role="tab" id="deploymentActionHeading">
                  <h5>
                    <a data-toggle="collapse" data-parent="#accordion" href="#deploymentActions" aria-expanded="true" aria-controls="deploymentActions">
                      Actions
                    </a>
                  </h5>
                </div>
                <div id="deploymentActions" class="collapse show" role="tabpanel" aria-labelledby="deploymentActionHeading">
                  <div class="card-block table-responsive">
                      <div id="collapseOne" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
                        <div class="card-block">
                            <button class="btn btn-block btn-primary" id="deploy">
                                Deploy
                            </button>
                            <hr/>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h5> <a> Containers In Deployment </a> </h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="deploymentContainersList">
                        <thead>
                            <tr>
                                <th> Name </th>
                                <th> Type </th>
                                <th> Memory </th>
                                <th> Network </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3">
              <div class="card">
                <div class="card-header" role="tab" id="deploymentCloudConfigHeading">
                  <h5>
                    <a data-toggle="collapse" data-parent="#accordion" href="#deploymentCloudConfig" aria-expanded="true" aria-controls="deploymentCloudConfig">
                      Cloud Configs
                    </a>
                  </h5>
                </div>
                <div id="deploymentCloudConfig" class="collapse show" role="tabpanel" aria-labelledby="deploymentCloudConfigHeading">
                  <div class="card-block table-responsive">
                      <table class="table table-bordered" id="deploymentCloudConfigTable">
                          <thead>
                              <tr>
                                  <th> Cloud Config </th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                  </div>
                </div>
              </div>
        </div>
    </div>
</div>

<script>

var currentDeployment = null;

function loadDeploymentsView()
{
    $(".boxSlide, #depoymentDetails").hide();
    $("#deploymentsOverview, #deploymentsBox").show();

    ajaxRequest(globalUrls.deployments.getAll, {}, (data)=>{
        data = $.parseJSON(data);

        var treeData = [{
            text: "Overview",
            icon: "fa fa-home",
            type: "deploymentsOverview",
            state: {
                selected: true
            }
        }];

        $.each(data, function(i, item){
            treeData.push({
                text: item.name,
                id: item.id,
                icon: "fas fa-archive",
                type: "deployment",
            });
        });


        $('#jsTreeSidebar').treeview({
            data: treeData,
            levels: 5,
            onNodeSelected: function(event, node) {
                if(node.type == "deployment"){
                    viewDeployment(node.id);
                } else if (node.type == "deploymentsOverview"){
                    loadDeploymentsView();
                }
            }
        });
    });
}

function viewDeployment(deploymentId)
{
    currentDeployment = deploymentId;
    let x = {deploymentId: deploymentId};
    $("#deploymentsOverview").hide();
    $("#depoymentDetails").show();
    ajaxRequest(globalUrls.deployments.getDeployment, x, function(data){
        data = $.parseJSON(data);
        let trs = "";
        $.each(data.cloudConfigs, function(i, item){
            trs += `<tr><td>${item.name}</td></tr>`
        });

        let c = "";

        $.each(data.containers, function(host, hostData){
            c += `<tr><td class="text-center bg-info" colspan="999"><h5> Host: ${host} </h5></td></tr>`;
            $.each(hostData.containers, function(_, container){
                c += `<tr>
                    <td><i class='${statusCodeIconMap[container.statusCode]}'></i>${container.name}</td>
                    <td>${container.type}</td>
                    <td>${formatBytes(container.state.memory.usage)}</td>
                    <td>`;
                $.each(container.state.network, function(name, details){
                    if(name == "lo"){
                        return;
                    }
                    c += `${details.addresses[0].address}`
                });
                c += `</td></tr>`
            });
        });

        $("#deploymentCloudConfigTable > tbody").empty().append(trs);
        $("#deploymentContainersList > tbody").empty().append(c);
    });
}

$("#deploymentsBox").on("click", "#createDeployment", function(){
    $("#modal-deployments-create").modal("show");
});

$("#deploymentsBox").on("click", "#deploy", function(){
    deploymentDeployObj.deploymentId = currentDeployment;
    $("#modal-deployments-deploy").modal("show");
});

</script>

<?php
    require __DIR__ . "/../modals/deployments/createDeployment.php";
    require __DIR__ . "/../modals/deployments/deploy.php";
?>
