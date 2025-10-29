<div id="deploymentsBox" class="boxSlide">
    <div id="deploymentsOverview">
        <div class="row">
            <div class="col-md-12">
                <div class="row border-bottom mb-2">
                    <div class="col-md-12 text-center">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                            <h4> Deployments </h4>
                            <div class="btn-toolbar float-end">
                              <div class="btn-group me-2">
                                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create Deployment" class="btn btn-primary" id="createDeployment">
                                      <i class="fas fa-plus"></i>
                                  </button>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="deploymentList">
            </div>
        </div>
    </div>
    <div id="depoymentDetails">
        <div class="row">
            <div class="col-md-12">
                <div class="row border-bottom mb-2">
                    <div class="col-md-12 text-center">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                            <h4 id="deploymentName"></h4>
                            <div class="btn-toolbar float-end">
                              <div class="btn-group me-2">
                                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Deploy Instances" class="btn btn-primary" id="deploy">
                                      <i class="fas fa-plus"></i>
                                  </button>
                                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Start Deployment" class="btn btn-success" id="startDeployment">
                                      <i class="fas fa-play" style="color: white !important;"></i>
                                  </button>
                                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Stop Deployment" class="btn btn-warning" id="stopDeployment">
                                      <i class="fas fa-stop"></i>
                                  </button>
                                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete Deployment" class="btn btn-danger" id="deleteDeployment">
                                      <i class="fas fa-trash"></i>
                                  </button>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="row mb-4" style="border-bottom: 1px solid black; padding-bottom: 10px">
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
                <div id="" class="card text-center actionCard bg-danger text-white">
                    <div class="card-body">Delete Deployment</div>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-md-3">
                  <div class="card bg-dark text-white mb-2">
                    <div class="card-header" role="tab" id="deploymentCloudConfigHeading">
                      <h5>
                        <a class="text-white" data-bs-toggle="collapse" data-parent="#accordion" href="#deploymentCloudConfig" aria-expanded="true" aria-controls="deploymentCloudConfig">
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
                  <div class="card bg-dark text-white">
                    <div class="card-header">
                      <h5>
                        Projects
                        <button class="btn btn-outline-primary btn-sm float-end" id="editDeploymentProjects">
                            <i class="fas fa-wrench"></i>
                        </button>
                      </h5>
                    </div>
                    <div class="card-body table-responsive">

                          <table class="table table-bordered table-dark" id="deploymentProjectTable">
                              <thead>
                                  <tr>
                                      <th> Host </th>
                                      <th> Project </th>
                                  </tr>
                              </thead>
                              <tbody>
                              </tbody>
                          </table>
                    </div>
                  </div>
            </div>
            <div class="col-md-9">
                <div class="card bg-dark text-white">
                    <div class="card-header bg-dark">
                        <h5>Instances In Deployment</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <table class="table table-bordered table-dark" id="deploymentContainersList">
                            <thead>
                                <tr>
                                    <th> Container </th>
                                    <th> Type </th>
                                    <th> Memory </th>
                                    <th> Network </th>
                                    <th> Last Started </th>
                                    <th> Phoned Home </th>
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
    <div class="card bg-dark text-white mb-2">
      <div class="card-header">
        <h5 class='text-white name'></h5>
      </div>
      <div class="card-body row text-white text-center">
        <div class="col-md-6">
          <h2>Memory <i class="fas fa-memory"></i></h2>
          <h4 class="text-uppercase text-muted memory"></h4>
        </div>
        <div class="col-md-6">
          <h2>Containers <i class="fas fa-box-open"></i></h2>
          <h4 class="text-uppercase text-muted containers"></h4>
        </div>
      </div>
    </div>`);
}

function loadDeploymentViewReq(req) {
    viewDeployment(req.data.deploymentId)
}

function loadDeploymentsSidebar(inputData = null){
    function _doResult(data){
        let hosts = `
        <li class="nav-item mb-2 mt-2 deployments-overview">
            <a class="nav-link p-0 ${$.isNumeric(currentDeployment) ? "" : "active"}" href="/deployments" data-navigo>
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>`;
        $.each(data, (_, item)=>{
            let active = item.id == currentDeployment ? "active" : "";
            hosts += `<li class="nav-item mb-2">
                <a class="nav-link p-0 ${active}" href="/deployments/${item.id}" data-navigo>
                    <i class="nav-icon fas fa-space-shuttle"></i> ${item.name}
                </a>
            </li>`
        });
        $("#sidebar-ul").empty().append(hosts)
        router.updatePageLinks()
    }
    if(inputData == null && $("#sidebar-ul").find("[href^='/deployments']").length == 0){
        ajaxRequest(globalUrls.deployments.getAll, {}, (data)=>{
            _doResult(makeToastr(data))
        });
    }else if(inputData !== null){
        _doResult(inputData);
    }else{
        $("#sidebar-ul").find(".active").removeClass("active");
        if($.isNumeric(currentDeployment)){
            $("#sidebar-ul").find(`.nav-link[href="/deployments/${currentDeployment}"]`).addClass("active")
        }else{
            $("#sidebar-ul").find(".nav-link:eq(0)").addClass("active")
        }
    }

}

function loadDeploymentsView()
{
    currentDeployment = null;
    setBreadcrumb("Deployments", "active", "/deployments");
    $(".sidebar-fixed").addClass("sidebar-lg-show");
    changeActiveNav(".viewDeployments")
    $(".boxSlide, #depoymentDetails").hide();
    $("#deploymentsOverview, #deploymentsBox").show();
    $("#deploymentList").empty();
    ajaxRequest(globalUrls.deployments.getAll, {}, (data)=>{
        data = makeToastr(data);

        if(data.hasOwnProperty("state") && data.state == "error"){
            $("#sidebar-ul").empty()
            makeToastr(data);
            return false;
        }

        loadDeploymentsSidebar(data);

        $.each(data, function(i, item){
            let box = emptyDeploymentBox();
            box.find(".name").html(`<a href="/deployments/${item.id}" data-navigo>${item.name}</a>`);

            box.find(".memory").text(formatBytes(item.containerDetails.totalMem));
            box.find(".containers").text(item.containerDetails.totalContainers);
            $("#deploymentList").append(box);

        });
        router.updatePageLinks()
    });
}

function viewDeployment(deploymentId)
{
    currentDeployment = deploymentId;
    loadDeploymentsSidebar()
    changeActiveNav(".viewDeployments")
    let x = {deploymentId: deploymentId};
    $(".boxSlide").hide();
    $(" #deploymentsBox").show();
    $("#deploymentsOverview").hide();
    $("#depoymentDetails").show();
    ajaxRequest(globalUrls.deployments.getDeployment, x, function(data){
        data = makeToastr(data);

        $("#deploymentName").text(`Deployment: ${data.details.name}`);

        addBreadcrumbs(["Deployments", data.details.name ], ["", "active"], false, ["/deployments"]);

        let trs = "";
        $.each(data.cloudConfigs, function(i, item){
            trs += `<tr><td>${item.name}</td></tr>`
        });

        let c = "";

        if(data.containers.length == 0){
            c += `<tr><td class='text-center' colspan="999">No Containers Deployed Yet</td></tr>`;
        }else{
            $.each(data.containers, function(host, hostData){
                c += `<tr><td class="text-center bg-secondary text-dark" colspan="999"><h5>Host: ${host}</h5></td></tr>`;
                $.each(hostData.containers, function(_, container){

                    let phonedHome = "<i class='fas fa-times'></i>";
                    let hasBeenSeenStarted = "<i class='fas fa-times'></i>";

                    if(container.hasOwnProperty("mosaicInfo")){
                        if(container.mosaicInfo.phoneHomeDate){
                            phonedHome = `<i class='fas fa-check'></i> ${moment.utc(container.mosaicInfo.phoneHomeDate).local().fromNow()}`;
                        }

                        if(container.mosaicInfo.lastStart){
                            hasBeenSeenStarted = moment.utc(container.mosaicInfo.lastStart).local().fromNow();
                        }
                    }

                    c += `<tr data-deployment-container="${container.name}">
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


                    c += `</td>
                    <td>${hasBeenSeenStarted}</td>
                    <td>${phonedHome}</td>
                    </tr>`
                });
            });
        }

        let projectTrs = "";

        $.each(data.projects, (_, project)=>{
            projectTrs += `<tr>
                <td>${project.hostAlias}</td>
                <td>${project.project}</td>
            </tr>`
        });


        $("#deploymentProjectTable > tbody").empty().append(projectTrs);
        $("#deploymentCloudConfigTable > tbody").empty().append(trs);
        $("#deploymentContainersList > tbody").empty().append(c);
        router.updatePageLinks();
    });
}

$("#deploymentsBox").on("click", "#editDeploymentProjects", function(){
    deploymentProjectsObj.deploymentId = currentDeployment
    deploymentProjectsObj.callback = function(){
        ajaxRequest(globalUrls.deployments.projects.getAll, {deploymentId: deploymentProjectsObj.deploymentId}, (data)=>{
            data = makeToastr(data)
            let projectTrs = "";

            $.each(data, (_, project)=>{
                projectTrs += `<tr>
                    <td>${project.hostAlias}</td>
                    <td>${project.project}</td>
                </tr>`
            });


            $("#deploymentProjectTable > tbody").empty().append(projectTrs);
        });
    }
    $("#modal-deployments-projects").modal("show")
});

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
    require __DIR__ . "/../modals/deployments/projects.php";
?>
