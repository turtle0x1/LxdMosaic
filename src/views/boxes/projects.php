<div id="projectsBox" class="boxSlide">
<div id="projectsOverview">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h4> Projects </h4>
                <div class="btn-toolbar float-end">
                  <div class="btn-group me-2">
                      <button data-toggle="tooltip" data-bs-placement="bottom" title="Create Project" class="btn btn-primary" id="createProject">
                          <i class="fas fa-plus"></i>
                      </button>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="projectCards">
        </div>
    </div>
</div>
<div id="projectDetails">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h4 id="projectName"></h4>
                <small id="projectDescription"></small>
                <div class="btn-toolbar float-end">
                  <div class="btn-group me-2">
                      <button data-toggle="tooltip" data-bs-placement="bottom" title="Rename Project"  class="btn btn-success" id="renameProject">
                          <i class="fas fa-edit"></i>
                      </button>
                      <button data-toggle="tooltip" data-bs-placement="bottom" title="Delete Project"  class="btn btn-danger" id="deleteProject">
                          <i class="fas fa-trash"></i>
                      </button>
                  </div>
                </div>
            </div>
        </div>
    </div>
<div class="row">
    <div class="col-md-4">
          <div class="card mb-2 bg-dark text-white">
            <div class="card-header bg-dark" role="tab" id="projectsActionHeading">
              <h5>
                <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#projectConfig" aria-expanded="true" aria-controls="projectConfig">
                  Config
                  <i class="float-end fas fa-cog"></i>
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
          <div class="card bg-dark text-white">
            <div class="card-header bg-dark" role="tab" id="projectsActionHeading">
              <h5>
                <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#projectConfig" aria-expanded="true" aria-controls="projectConfig">
                  Restrictions
                  <i class="float-end fas fa-lock"></i>
                </a>
              </h5>
            </div>
            <div id="projectConfig" class="collapse show" role="tabpanel" aria-labelledby="projectsActionHeading">
              <div class="card-block bg-dark table-responsive">
                  <div id="collapseOne" class="collapse in show" role="tabpanel" >
                      <table class="table table-dark table-bordered" id="restrictionsListTable">
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
    <div class="col-md-4">
        <div class="card bg-dark text-white">
          <div class="card-header bg-dark" role="tab" id="projectsActionHeading">
            <h5>
              <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#projectUsedBy" aria-expanded="true" aria-controls="projectUsedBy">
                Users
                <i class="float-end fas fa-users"></i>
              </a>
            </h5>
          </div>
          <div id="projectUsedBy" class="collapse show" role="tabpanel" aria-labelledby="projectsActionHeading">
            <div class="card-block bg-dark table-responsive">
                <div id="collapseOne" class="collapse in show" role="tabpanel" >
                    <table class="table table-dark table-bordered" id="projectUsersTable">
                        <thead>
                            <tr>
                                <th> User </th>
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
    <div class="col-md-4">
        <div class="card bg-dark text-white">
          <div class="card-header bg-dark" role="tab" id="projectsActionHeading">
            <h5>
              <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#projectUsedBy" aria-expanded="true" aria-controls="projectUsedBy">
                Used By
                <i class="fas fa-layer-group float-end"></i>
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
    if(host.hostOnline == false){
        disabled = "disabled text-warning text-strikethrough";
    }

    let currentId = "a";

    hosthtml += `<li class="mb-2">
        <a class="d-inline href="#">
            <i class="fas fa-server"></i> ${host.alias}
        </a>
        <button class="btn  btn-outline-secondary btn-sm btn-toggle align-items-center rounded d-inline ${disabled} float-end me-2" data-bs-toggle="collapse" data-bs-target="#${currentId}" aria-expanded="true">
            <i class="fas fa-caret-down"></i>
        </button>
        <div class=" mt-2 bg-dark text-white" id="${currentId}">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small hostInstancesUl" style="display: inline;">`

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

function makeProjectCard(hostName, projects){

    if(Object.keys(projects).length == 0){
        return "";
    }

    let thead = "<th>project</th>";
    let tbody = "";

    let formatBytesKeys = ["limits.memory", "limits.disk"];
    let formatNanoSecondsKeys = ["limits.cpu"];


    $.each(projects[Object.keys(projects)[0]], (limit, value)=>{
        let lThead = limit.replace("limits.", "");
        thead += `<th>${lThead}</th>`;
    });

    $.each(projects, (projectName, projectValues)=>{
        tbody += `<tr><td>${projectName}</td>`;
        $.each(projectValues, (limit, value)=>{

            let lTxt = value.limit == null ? '<i class="fas fa-infinity"></i>' : value.limit;
            let vText = value.value;
            if(formatBytesKeys.includes(limit)){
                vText = formatBytes(vText)
            }else if (formatNanoSecondsKeys.includes(limit)){
                vText = nanoSecondsToHourMinutes(vText);
            }

            tbody += `<td>${vText} / ${lTxt}</td>`;
        });
        tbody += "</tr>";
    })

    return `<div class="card mb-2 bg-dark text-white">
        <div class="card-header">
            <h4><i class='fas fa-server me-2'></i>${hostName}</h4>
        </div>
        <div class="card-body">
            <table class="table table-dark table-bordered">
                <thead>
                    <tr>
                    ${thead}
                    </tr>
                </thead>
                <tbody>
                ${tbody}
                </tbody>
            </table>
        </div>
    </div>`;
}

function loadProjectView()
{
    $(".boxSlide, #projectDetails").hide();
    $("#projectsOverview, #projectsBox").show();
    $("#projectCards").empty().append(`<h4 class='text-center'><i class="fas fa-cog fa-spin"></i></h4>`)
    ajaxRequest(globalUrls.projects.getOverview, {}, function(data){
        data = $.parseJSON(data);
        let cards = "";

        $.each(data.clusters, (clusterIndex, cluster)=>{
            $.each(cluster.members, (_, host)=>{
                cards += makeProjectCard(`Cluster ${clusterIndex}`, host.projects);
            })
        });

        $.each(data.standalone.members, (_, host)=>{
            cards += makeProjectCard(host.alias, host.projects);
        });
        $("#projectCards").empty().append(cards);
    })


    ajaxRequest(globalUrls.projects.getAllFromHosts, {}, function(data){

        data = $.parseJSON(data);

        let hosts = `
        <li class="nav-item projects-overview">
            <a class="nav-link text-info" href="#">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>`;

        $.each(data.clusters, (clusterIndex, cluster)=>{
            hosts += `<li class="c-sidebar-nav-title text-success ps-1 pt-2"><u>Cluster ${clusterIndex}</u></li>`;
            $.each(cluster.members, (_, host)=>{
                hosts = makeProjectHostSidebarHtml(hosts, host)
            })
        });

        hosts += `<li class="c-sidebar-nav-title text-success ps-1 pt-2"><u>Standalone Hosts</u></li>`;

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
        let emptyProject = data.used_by.length < 2;
        if(data.used_by.length == 0){
            projectUsedBy += "<tr><td class='text-center'><i class='fas fa-info-circle text-info me-2'></i>Not used</td></tr>"
        }else{
            $.each(data.used_by, function(i, item){
                projectUsedBy += `<tr><td>${item}</td></tr>`;
            });
        }

        $("#projectsBox #deleteProject").attr("disabled", !emptyProject);
        $("#projectsBox #renameProject").attr("disabled", !emptyProject);

        $("#projectUsedByTable > tbody").empty().append(projectUsedBy);

        let projectConfig = "";
        let restrictionsConfig = "";

        $.each(data.config, function(i, item){
            if(i.startsWith("features")){
                projectConfig += `<tr><td>${i.replace("features.", "")}</td><td>${item}</td></tr>`;
            }else{
                restrictionsConfig += `<tr><td>${i.replace("restricted.", "")}</td><td>${item}</td></tr>`;
            }
        });

        if(restrictionsConfig == ""){
            restrictionsConfig = `<tr><td colspan="999" class="text-center"><i class="fas fa-info-circle text-info me-2"></i>No Restrictions</td></tr>`
        }

        let usersList = "";
        $.each(data.users, (_, user)=>{
            usersList += `<tr><td>${user}</td></tr>`
        });

        $("#projectUsersTable > tbody").empty().append(usersList);
        $("#restrictionsListTable > tbody").empty().append(restrictionsConfig);
        $("#projectConfigTable > tbody").empty().append(projectConfig);
    });
}


$("#projectsBox").on("click", "#deleteProject", function(){
    $.confirm({
        title: "Delete Project?!",
        content: `<i class="fas fa-info-circle text-info me-2"></i>Users currently using this project will be assigned back to <code>default</code> project`,
        theme: 'dark',
        buttons: {
            cancel: {},
            ok: {
                btnClass: "btn btn-danger",
                action: function(){
                    ajaxRequest(globalUrls.projects.delete, currentProject, function(data){
                        data = makeToastr(data);
                        if(data.state == "success"){
                            loadProjectView();

                        }
                    });
                }
            }
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
