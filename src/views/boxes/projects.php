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
              <a id="projectName" data-toggle="collapse" data-parent="#accordion" href="#projectActions" aria-expanded="true" aria-controls="projectActions">
              </a>
            </h5>
          </div>
          <div id="projectActions" class="collapse show" role="tabpanel" aria-labelledby="projectsActionHeading">
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
                    <div class="card-block">
                    </div>
                  </div>
              </div>
            </div>
          </div>
    </div>
    <div class="col-md-6">
    </div>
    <div class="col-md-3">
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
        let description = data.description == "" ? "<b> No description </b>" : data.description;
        $("#projectName").text(data.name);
        $("#projectDescription").html(description);
    });
}


$("#projectsBox").on("click", "#createProject", function(){
    $("#modal-projects-create").modal("show");
});
</script>

<?php
    require __DIR__ . "/../modals/projects/createProject.php";
?>
