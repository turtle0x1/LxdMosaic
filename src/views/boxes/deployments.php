<div id="deploymentsBox" class="boxSlide">
    <div id="deploymentsOverview" class="row">
        <div class="col-md-9">
              <div class="card">
                <div class="card-header bg-info" role="tab" id="headingOne">
                  <h5>
                    <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                      Deployments
                    </a>
                  </h5>
                </div>
                <div id="cloudConfigDescription" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
                  <div class="card-block bg-dark">
                      Deployments are used to deploy multiple cloud configs to multiple
                      containers.
                  </div>
                </div>
              </div>
              <div id="deploymentList">
              </div>
        </div>
        <div class="col-md-3">
              <div class="card">
                <div class="card-header bg-info" role="tab" id="headingOne">
                  <h5>
                    <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Actions
                    </a>
                  </h5>
                </div>
                <div id="collapseOne" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
                  <div class="card-block bg-dark">
                      <button class="btn btn-block btn-primary" id="createDeployment">
                          Create
                      </button>
                  </div>
                </div>
              </div>
        </div>
    </div>
    <div id="depoymentDetails">
        <div class="row mb-4" style="border-bottom: 1px solid black; padding-bottom: 10px">
            <h4 class="text-white" id="deploymentName"> Deployment Name </h4>
        </div>
        <div class="row mb-4" style="border-bottom: 1px solid black; padding-bottom: 10px">
            <div class="col-md-3">
                <div id="deploy" class="card text-center actionCard bg-primary text-white">
                    <div class="card-body">Deploy Containers</div>
                </div>
            </div>
            <div class="col-md-3">
                <div id="startDeployment" class="card text-center actionCard bg-success text-white">
                    <div class="card-body">Start Deployment</div>
                </div>
            </div>
            <div class="col-md-3">
                <div id="stopDeployment" class="card text-center actionCard bg-warning text-white">
                    <div class="card-body">Stop Deployment</div>
                </div>
            </div>
            <div class="col-md-3">
                <div id="deleteDeployment" class="card text-center actionCard bg-danger text-white">
                    <div class="card-body">Delete Deployment</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                  <div class="card">
                    <div class="card-header text-center bg-info" role="tab" id="deploymentCloudConfigHeading">
                      <h5>
                        <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#deploymentCloudConfig" aria-expanded="true" aria-controls="deploymentCloudConfig">
                        Cloud Configs
                        </a>
                      </h5>
                    </div>
                    <div id="deploymentCloudConfig" class="collapse show" role="tabpanel" aria-labelledby="deploymentCloudConfigHeading">
                      <div class="card-block bg-dark table-responsive">
                          <table class="table table-bordered table-dark" id="deploymentCloudConfigTable">
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
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-info text-center">
                        <h5> <a> Containers In Deployment </a> </h5>
                    </div>
                    <div class="card-body bg-dark">
                        <table class="table table-bordered table-dark" id="deploymentContainersList">
                            <thead>
                                <tr>
                                    <th> Container </th>
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
        </div>
    </div>
</div>

<script>

var currentDeployment = null;

var emptyDeploymentBox = function(){
    return $(`
    <div class="brand-card" id="">
      <div class="brand-card-header bg-info">
        <h5 class='text-white name'></h5>
      </div>
      <div class="brand-card-body bg-dark">
        <div>
          <div class="text-value">Memory <i class="fas fa-memory"></i></div>
          <div class="text-uppercase text-muted memory"></div>
        </div>
        <div>
          <div class="text-value">Containers <i class="fas fa-box-open"></i></div>
          <div class="text-uppercase text-muted containers"></div>
        </div>
      </div>
    </div>`);
}

function loadDeploymentsView()
{
    $(".boxSlide, #depoymentDetails").hide();
    $("#deploymentsOverview, #deploymentsBox").show();
    $("#deploymentList").empty();
    addBreadcrumbs(["Deployments"], ["active"], false);
    ajaxRequest(globalUrls.deployments.getAll, {}, (data)=>{
        data = $.parseJSON(data);

        let hosts = `
        <li class="nav-item active deployments-overview">
            <a class="nav-link" href="#">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>`;

        $.each(data, function(i, item){
            let box = emptyDeploymentBox();
            box.find(".name").html(`
                <a class='viewDeployment' href="#" data-deployment-id="${item.id}"><u>${item.name}</u>
                </a>`);
            box.find(".memory").text(formatBytes(item.containerDetails.totalMem));
            box.find(".containers").text(item.containerDetails.totalContainers);
            $("#deploymentList").append(box);
            hosts += `<li class="nav-item view-deployment" data-id="${item.id}" >
                <a class="nav-link" href="#">
                    <i class="nav-icon fas fa-space-shuttle"></i> ${item.name}
                </a>
            </li>`
        });
        $("#sidebar-ul").empty().append(hosts);
    });
}

$("#sidebar-ul").on("click", ".view-deployment", function(){
    viewDeployment($(this).data("id"))
});

function viewDeployment(deploymentId)
{
    currentDeployment = deploymentId;
    let x = {deploymentId: deploymentId};
    $("#deploymentsOverview").hide();
    $("#depoymentDetails").show();
    ajaxRequest(globalUrls.deployments.getDeployment, x, function(data){
        data = $.parseJSON(data);

        $("#deploymentName").text(`Deployment: ${data.details.name}`);
        addBreadcrumbs(["Deployments", data.details.name ], ["", "active"], false);

        let trs = "";
        $.each(data.cloudConfigs, function(i, item){
            trs += `<tr><td>${item.name}</td></tr>`
        });

        let c = "";

        if(data.containers.length == 0){
            c += `<tr><td class='text-center' colspan="999">No Containers Deployed Yet</td></tr>`;
        }else{
            $.each(data.containers, function(host, hostData){
                c += `<tr><td class="text-center bg-secondary text-white" colspan="999"><h5> Host: ${host} </h5></td></tr>`;
                $.each(hostData.containers, function(_, container){
                    c += `<tr>
                        <td><i class='${statusCodeIconMap[container.statusCode]}'></i> ${container.name}</td>
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
        }


        $("#deploymentCloudConfigTable > tbody").empty().append(trs);
        $("#deploymentContainersList > tbody").empty().append(c);
    });
}

$("#deploymentsBox").on("click", "#startDeployment", function(){
    $.confirm({
        title: 'Start Deployment',
        content: 'This will start all containers in the deployment!',
        buttons: {
            cancel: function () {},
            yes: {
                btnClass: 'btn-success',
                action: function () {
                    this.buttons.yes.setText('<i class="fa fa-cog fa-spin"></i>Starting..'); // let the user know
                    this.buttons.yes.disable();
                    this.buttons.cancel.disable();
                    var modal = this;
                    ajaxRequest(globalUrls.deployments.startDeployment, {deploymentId: currentDeployment}, (data)=>{
                        makeToastr(data);
                        viewDeployment(currentDeployment);
                        modal.close();
                    });
                    return false;
                }
            }
        }
    });
});

$("#deploymentsBox").on("click", ".viewDeployment", function(e){
    e.preventDefault();
    viewDeployment($(this).data("deploymentId"));
});

$("#deploymentsBox").on("click", "#deleteDeployment", function(){
    $.confirm({
        title: 'Delete Deployment?',
        content: 'This will remove all containers & profiles in the deployment!',
        buttons: {
            cancel: function () {},
            yes: {
                btnClass: 'btn-danger',
                action: function () {
                    this.buttons.yes.setText('<i class="fa fa-cog fa-spin"></i>Deleting..'); // let the user know
                    this.buttons.yes.disable();
                    this.buttons.cancel.disable();
                    var modal = this;
                    ajaxRequest(globalUrls.deployments.delete, {deploymentId: currentDeployment}, (data)=>{
                        makeToastr(data);
                        modal.close();
                        loadDeploymentsView();
                    });
                    return false;
                }
            }
        }
    });
});

$("#deploymentsBox").on("click", "#stopDeployment", function(){
    $.confirm({
        title: 'Stop Deployment',
        content: 'This will stop all containers in the deployment!',
        buttons: {
            cancel: function () {},
            yes: {
                btnClass: 'btn-danger',
                action: function () {
                    this.buttons.yes.setText('<i class="fa fa-cog fa-spin"></i>Stopping..'); // let the user know
                    this.buttons.yes.disable();
                    this.buttons.cancel.disable();
                    var modal = this;
                    ajaxRequest(globalUrls.deployments.stopDeployment, {deploymentId: currentDeployment}, (data)=>{
                        makeToastr(data);
                        viewDeployment(currentDeployment);
                        modal.close();
                    });
                    return false;
                }
            }
        }
    });
});

$("#deploymentsBox").on("click", "#createDeployment", function(){
    createDeploymentCallback = function(deploymentId){
        loadDeploymentsView();
        viewDeployment(deploymentId)
    };
    $("#modal-deployments-create").modal("show");
});

$("#deploymentsBox").on("click", "#deploy", function(){
    deploymentDeployObj.deploymentId = currentDeployment;
    deploymentDeployObj.callback = function(){
         setTimeout(function(){
             viewDeployment(currentDeployment);
         }, 1000)
    };
    $("#modal-deployments-deploy").modal("show");
});

</script>

<?php
    require __DIR__ . "/../modals/deployments/createDeployment.php";
    require __DIR__ . "/../modals/deployments/deploy.php";
?>
