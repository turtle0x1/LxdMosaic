<div id="projectsBox" class="boxSlide">
<div id="projectsOverview" class="row">
    <div class="col-md-9">
          <div class="card">
            <div class="card-header bg-info" role="tab" >
              <h5>
                <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                  Projects
                </a>
              </h5>
            </div>
            <div id="cloudConfigDescription" class="collapse in show" role="tabpanel" >
              <div class="card-block bg-dark">
                  Projects are used to split up an lxd server. You can read
                  more about them here<a href="https://lxd.readthedocs.io/en/latest/projects/">
                  here in the lxd docs. </a>
              </div>
            </div>
          </div>
    </div>
    <div class="col-md-3">
          <div class="card">
            <div class="card-header bg-info" role="tab" >
              <h5>
                <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Actions
                </a>
              </h5>
            </div>
            <div id="collapseOne" class="collapse in show" role="tabpanel" >
              <div class="card-block bg-dark">
                  <button class="btn btn-block btn-primary" id="createProject">
                      Create
                  </button>
              </div>
            </div>
          </div>
    </div>
</div>
<div id="projectDetails">
<div class="row" style="margin-bottom: 10px;">
    <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-info" role="tab" id="projectsActionHeading">
            <h5>
              <a class="text-white" id="projectName" data-toggle="collapse" data-parent="#accordion" href="#projectDetailsHeading" aria-expanded="true" aria-controls="projectDetailsHeading">
              </a>
            </h5>
          </div>
          <div id="projectDetailsHeading" class="collapse show" role="tabpanel" aria-labelledby="projectsActionHeading">
            <div class="card-block bg-dark" id="projectDescription">
            </div>
          </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
          <div class="card">
            <div class="card-header bg-info" role="tab" id="projectsActionHeading">
              <h5>
                <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#projectActions" aria-expanded="true" aria-controls="projectActions">
                  Actions
                  <i class="float-right fas fa-edit"></i>
                </a>
              </h5>
            </div>
            <div id="projectActions" class="collapse show" role="tabpanel" aria-labelledby="projectsActionHeading">
              <div class="card-block bg-dark table-responsive">
                  <div id="collapseOne" class="collapse in show" role="tabpanel" >
                    <div class="card-block text-center">
                        <button class="btn btn-block btn-warning" id="renameProject">
                            Rename
                        </button>
                        <button class="btn btn-block btn-danger" id="deleteProject">
                            Delete
                        </button>
                    </div>
                  </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header bg-info" role="tab" id="projectsActionHeading">
              <h5>
                <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#projectConfig" aria-expanded="true" aria-controls="projectConfig">
                  Config
                  <i class="float-right fas fa-cog"></i>
                </a>
              </h5>
            </div>
            <div id="projectConfig" class="collapse show" role="tabpanel" aria-labelledby="projectsActionHeading">
              <div class="card-block bg-dark table-responsive">
                  <div id="collapseOne" class="collapse in show" role="tabpanel" >
                      <table class="table table-dark table-bordered" id="projectConfigTable">
                          <thead>
                              <tr>
                                  <th> Key </th>
                                  <th> Value </th>
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
    <div class="col-md-9">
        <div class="card">
          <div class="card-header bg-info" role="tab" id="projectsActionHeading">
            <h5>
              <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#projectUsedBy" aria-expanded="true" aria-controls="projectUsedBy">
                Used By
                <i class="float-right fas fa-users"></i>
              </a>
            </h5>
          </div>
          <div id="projectUsedBy" class="collapse show" role="tabpanel" aria-labelledby="projectsActionHeading">
            <div class="card-block bg-dark table-responsive">
                <div id="collapseOne" class="collapse in show" role="tabpanel" >
                    <table class="table table-dark table-bordered" id="projectUsedByTable">
                        <thead>
                            <tr>
                                <th> Item </th>
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
</div>
</div>

<script>

var currentProject = {
    host: null,
    project: null
};

function makeProjectHostSidebarHtml(hosthtml, host){
    let disabled = "";
    if(host.online == false){
        disabled = "disabled text-warning text-strikethrough";
    }

    hosthtml += `<li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle ${disabled}" href="#">
            <i class="fas fa-server"></i> ${host.alias}
        </a>
        <ul class="nav-dropdown-items">`;

    if(host.projects.length > 0){
        $.each(host.projects, function(_, project){
            hosthtml += `<li class="nav-item view-project"
                data-host-id="${host.hostId}"
                data-project="${project}"
                data-alias="${host.alias}">
              <a class="nav-link" href="#">
                <i class="nav-icon fa fa-project-diagram"></i>
                ${project}
              </a>
            </li>`;
        });
    }else{
        hosthtml += `<li class="nav-item text-center text-warning">No Projects</li>`;
    }

    hosthtml += "</ul></li>";
    return hosthtml;
}

function loadProjectView()
{
    $(".boxSlide, #projectDetails").hide();
    $("#projectsOverview, #projectsBox").show();
    ajaxRequest(globalUrls.projects.getAllFromHosts, {}, function(data){

        data = $.parseJSON(data);

        let hosts = `
        <li class="nav-item projects-overview">
            <a class="nav-link text-info" href="#">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>`;

        $.each(data.clusters, (clusterIndex, cluster)=>{
            hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Cluster ${clusterIndex}</u></li>`;
            $.each(cluster.members, (_, host)=>{
                hosts = makeProjectHostSidebarHtml(hosts, host)
            })
        });

        hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Standalone Hosts</u></li>`;

        $.each(data.standalone.members, (_, host)=>{
            hosts = makeProjectHostSidebarHtml(hosts, host)
        });


        $("#sidebar-ul").empty().append(hosts);
    });
}

$("#sidebar-ul").on("click", ".view-project", function(){
     viewProject($(this).data("project"), $(this).data("hostId"), $(this).data("alias"));
})

function viewProject(project, hostId, hostAlias){
    currentProject.project = project;
    currentProject.hostId = hostId;
    currentProject.hostAlias = hostAlias;
    addBreadcrumbs([hostAlias, project], ["", "active"], true)
    ajaxRequest(globalUrls.projects.info, currentProject, (data)=>{
        data = $.parseJSON(data);
        $("#projectsOverview").hide();
        $("#projectDetails").show();
        let emptyDescription = data.description == "";
        let description = emptyDescription ? "<b style='color: red;'> No description </b>" : data.description;
        let collapseDescription = emptyDescription ? "hide" : "show";
        $("#projectName").text(data.name);
        $("#projectDescription").html(description);
        $("#projectDetailsHeading").collapse(collapseDescription);
        let projectUsedBy = "";
        let emptyProject = data.used_by.length == 0;
        if(emptyProject){
            projectUsedBy += "<tr><td class='text-center'><b style='color: red;'>Not Used</b></td></tr>"
        }else{
            $.each(data.used_by, function(i, item){
                projectUsedBy += `<tr><td>${item}</td></tr>`;
            });
        }

        $("#projectsBox #deleteProject").attr("disabled", !emptyProject);
        $("#projectsBox #renameProject").attr("disabled", !emptyProject);

        $("#projectUsedByTable > tbody").empty().append(projectUsedBy);

        let projectConfig = "";
        $.each(data.config, function(i, item){
            projectConfig += `<tr><td>${i.replace("features.", "")}</td><td>${item}</td></tr>`;
        });
        $("#projectConfigTable > tbody").empty().append(projectConfig);
    });
}


$("#projectsBox").on("click", "#deleteProject", function(){
    ajaxRequest(globalUrls.projects.delete, currentProject, function(data){
        data = makeToastr(data);
        if(data.state == "success"){
            loadProjectView();

        }
    });
});

$("#projectsBox").on("click", "#createProject", function(){
    $("#modal-projects-create").modal("show");
});

$("#projectsBox").on("click", "#renameProject", function(){
    renameProjectObj.hostId = currentProject.hostId;
    renameProjectObj.project = currentProject.project;
    $("#modal-projects-rename").modal("show");
});
</script>

<?php
    require __DIR__ . "/../modals/projects/createProject.php";
    require __DIR__ . "/../modals/projects/renameProject.php";
?>
