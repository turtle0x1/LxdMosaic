<div id="projectsBox" class="boxSlide">
<div id="projectsOverview" class="row">
    <div class="col-md-9">
          <div class="card">
            <div class="card-header" role="tab" id="headingOne">
              <h5>
                <a data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                  Projects
                </a>
              </h5>
            </div>
            <div id="cloudConfigDescription" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
              <div class="card-block">
                  Projects are used to split up an lxd server. You can read
                  more about them here<a href="https://lxd.readthedocs.io/en/latest/projects/">
                  here in the lxd docs. </a>
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
          <div class="card-header" role="tab" id="projectsActionHeading">
            <h5>
              <a id="projectName" data-toggle="collapse" data-parent="#accordion" href="#projectDetailsHeading" aria-expanded="true" aria-controls="projectDetailsHeading">
              </a>
            </h5>
          </div>
          <div id="projectDetailsHeading" class="collapse show" role="tabpanel" aria-labelledby="projectsActionHeading">
            <div class="card-block" id="projectDescription">
            </div>
          </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
          <div class="card">
            <div class="card-header" role="tab" id="projectsActionHeading">
              <h5>
                <a data-toggle="collapse" data-parent="#accordion" href="#projectActions" aria-expanded="true" aria-controls="projectActions">
                  Actions
                </a>
              </h5>
            </div>
            <div id="projectActions" class="collapse show" role="tabpanel" aria-labelledby="projectsActionHeading">
              <div class="card-block table-responsive">
                  <div id="collapseOne" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
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
    </div>
    <div class="col-md-5">
        <div class="card">
          <div class="card-header" role="tab" id="projectsActionHeading">
            <h5>
              <a data-toggle="collapse" data-parent="#accordion" href="#projectUsedBy" aria-expanded="true" aria-controls="projectUsedBy">
                Used By
              </a>
            </h5>
          </div>
          <div id="projectUsedBy" class="collapse show" role="tabpanel" aria-labelledby="projectsActionHeading">
            <div class="card-block table-responsive">
                <div id="collapseOne" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
                    <table class="table table-bordered" id="projectUsedByTable">
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
    <div class="col-md-4">
        <div class="card">
          <div class="card-header" role="tab" id="projectsActionHeading">
            <h5>
              <a data-toggle="collapse" data-parent="#accordion" href="#projectConfig" aria-expanded="true" aria-controls="projectConfig">
                Config
              </a>
            </h5>
          </div>
          <div id="projectConfig" class="collapse show" role="tabpanel" aria-labelledby="projectsActionHeading">
            <div class="card-block table-responsive">
                <div id="collapseOne" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
                    <table class="table table-bordered" id="projectConfigTable">
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
</div>
</div>
</div>

<script>

var currentProject = {
    host: null,
    project: null
};

function loadProjectView()
{
    ajaxRequest(globalUrls.projects.getAllFromHosts, {}, function(data){
        var treeData = [{
            text: "Overview",
            icon: "fa fa-home",
            type: "projectsOverview",
            state: {
                selected: true
            }
        }];

        $(".boxSlide, #projectDetails").hide();
        $("#projectsOverview, #projectsBox").show();
        data = $.parseJSON(data);
        $.each(data, function(hostName, projects){
            let hostProjects = [];
            $.each(projects, function(i, projectName){
                let x = {
                    text: projectName,
                    icon: "fa fa-user",
                    type: "project",
                    id: projectName,
                    host: hostName
                };
                // if(hostName == selectedHost && projectName == selectedProfile ){
                //     matched = true;
                //     x.state = {
                //         selected: true
                //     };
                // }
                hostProjects.push(x);
            });
            treeData.push({
                text: hostName,
                nodes: hostProjects,
                icon: "fa fa-server"
            })
        });
        $('#jsTreeSidebar').treeview({
            data: treeData,         // data is not optional
            levels: 5,
            onNodeSelected: function(event, node) {
                if(node.type == "project"){
                    viewProject(node.id, node.host);
                } else if (node.type == "projectsOverview"){
                    $(".boxSlide, #projectDetails").hide();
                    $("#projectsOverview, #projectsBox").show();
                }
            }
        });

        changeActiveNav(".viewProjects");
    });
}

function viewProject(project, host){
    currentProject.project = project;
    currentProject.host =    host;
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
                projectUsedBy += "<tr><td>" + item + "</td></tr>"
            });
        }

        $("#projectsBox #deleteProject").attr("disabled", !emptyProject);
        $("#projectsBox #renameProject").attr("disabled", !emptyProject);
        
        $("#projectUsedByTable > tbody").empty().append(projectUsedBy);

        let projectConfig = "";
        $.each(data.config, function(i, item){
            projectConfig += "<tr><td>" + i.replace("features.", "") + "</td><td>" + item + "</td></tr>"
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
    renameProjectObj.host = currentProject.host;
    renameProjectObj.project = currentProject.project;
    $("#modal-projects-rename").modal("show");
});
</script>

<?php
    require __DIR__ . "/../modals/projects/createProject.php";
    require __DIR__ . "/../modals/projects/renameProject.php";
?>
