<!-- Modal -->
<div class="modal fade" id="modal-deployments-projects" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deployment Projects</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 80vh; height: 80vh; padding-top: 1px; overflow: scroll;">
                <div class="row">
                    <div class="col-md-12 pt-3">
                        <div id="availableToAssignProjects"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="update" class="btn btn-primary">
                    Update
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    var deploymentProjectsObj = {
        deploymentId: null,
        callback: null
    }

    $("#modal-deployments-projects").on("shown.bs.modal", function() {
        $("#deployCloudConfigTable > tbody").empty();
        ajaxRequest(globalUrls.deployments.projects.getAll, {deploymentId: deploymentProjectsObj.deploymentId}, (currentProjectsData)=>{
            currentProjectsData = makeToastr(currentProjectsData);
            let currentProjectStruct = {}
            $.each(currentProjectsData, (_, project)=>{
                if(!currentProjectStruct.hasOwnProperty(project.hostAlias)){
                    currentProjectStruct[project.hostAlias] = []
                }
                currentProjectStruct[project.hostAlias].push(project.project)
            });
            ajaxRequest(globalUrls.universe.getEntities, {entity: "projects"}, (data)=>{
                data = makeToastr(data)
                let availableToAssign = "";
                $.each(data.standalone.members, (_, member)=>{
                    if(member.hostOnline == "0"){
                        return true;
                    }
                    availableToAssign += `<div><h4><i class="fas fa-server me-2"></i>${member.alias}</h4>`
                    $.each(member.projects, (_, project)=>{
                        let active = "secondary";
                        if(currentProjectStruct.hasOwnProperty(member.alias) && currentProjectStruct[member.alias].includes(project)){
                            active = "primary"
                        }
                        availableToAssign += `<span style="cursor: pointer" class="badge bg-${active} m-2 deploymentProjectBadge" data-host-id="${member.hostId}" data-project="${project}">
                            <h5>
                                <i class="fas fa-project-diagram me-2"></i>${project}
                            </h5>
                        </span>`
                    });
                    availableToAssign += `</div>`
                });
                $("#availableToAssignProjects").empty().append(availableToAssign);
            });
        })
    });

    $("#modal-deployments-projects").on("click", ".deploymentProjectBadge", function() {
        if($(this).hasClass("bg-primary")){
            $(this).removeClass("bg-primary")
            $(this).addClass("bg-secondary")
        }else{
            $(this).removeClass("bg-secondary")
            $(this).addClass("bg-primary")
        }
    });

    $("#modal-deployments-projects").on("click", "#update", function() {
        let newProjectsLayout = [];
        let btn = $(this)
        btn.attr("disabled", true);
        $(".deploymentProjectBadge.bg-primary").each(function(){
            let badge = $(this);
            newProjectsLayout.push(badge.data())
        });

        let x = {deploymentId: deploymentProjectsObj.deploymentId, newProjectsLayout}
        ajaxRequest(globalUrls.deployments.projects.set, x, (data)=>{
            data = makeToastr(data)
            if(data.state == "success"){
                if(typeof deploymentProjectsObj.callback === "function"){
                    deploymentProjectsObj.callback();
                }
                $("#modal-deployments-projects").modal("hide")
            }

            btn.attr("disabled", false);
        });
    });
</script>
