    <!-- Modal -->
<div class="modal fade" id="modal-projects-access" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Project Access</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-md-6">
                  <h5>Manage Users</h5>
                  <div class="form-group">
                      <b> New Users </b>
                      <input class="form-control" id="projectAccessUserSearch"/>
                  </div>
                  <div class="">
                      <button class="btn btn-primary float-right" id="grantAccess">
                          Add Users
                      </button>
                  </div>
              </div>
              <div class="col-md-6 border-left">
                  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3">
                      <h5>Users With Access</h5>
                      <button class="btn btn-sm btn-danger mb-2">
                          Revoke All
                      </button>
                  </div>

                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1"><i class="fas fa-filter"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="Filter users..." aria-label="Username" aria-describedby="basic-addon1">
                  </div>
                  <div id="usersWithAccess">
                  </div>
              </div>
          </div>

      </div>
    </div>
  </div>
</div>
<script>
var projectAccessObj = {
    hostId: null,
    project: null
}

$("#projectAccessUserSearch").tokenInput(globalUrls.settings.users.search, {
    queryParam: "search",
    propertyToSearch: "name",
    theme: "facebook",
    responseIdKey: "id"
});

$("#modal-projects-access").on("click", "#grantAccess",  function(){
    let usersToGrant = mapObjToSignleDimension($("#projectAccessUserSearch").tokenInput("get"), "id");
    let x = {...{targetUsers: usersToGrant}, ...projectAccessObj}
    ajaxRequest(globalUrls.settings.users.allowedProjects.grantAcessToProject, x, (data)=>{
        data = makeToastr(data);
        if(data.state == "error"){
            return false;
        }
        $("#projectAccessUserSearch").tokenInput("clear");
        $("#modal-projects-access").trigger("shown.bs.modal");
    });
});

$("#modal-projects-access").on("hide.bs.modal",  function(){
    $("#usersWithAccess").empty();
    $("#projectAccessUserSearch").tokenInput("clear");
    loadProjectAccesOverview();
});

$("#modal-projects-access").on("shown.bs.modal", function(){
    if(projectAccessObj.hostId == null || projectAccessObj.hostId == ""){
        $("#modal-projects-access").modal("toggle");
        alert("current hostId isn't set");
        return false;
    }else if(projectAccessObj.project == null || projectAccessObj.project == ""){
        $("#modal-projects-access").modal("toggle");
        alert("current project isn't set");
        return false;
    }

    ajaxRequest(globalUrls.projects.users.getUsersWithAccess, projectAccessObj, (data)=>{
        data = makeToastr(data);

        let html = "";

        if(data.length == 0){
            html += `<div class='text-center'><i class='fas fa-info-circle text-primary'></i> No users have access other than admin </i>`
        }else{
            $.each(data, (_, user)=>{
                html += `<span class='badge badge-secondary m-2' id="${user.userId}"><i class='fas fa-user text-primary mr-2'></i>${user.userName}<i class="ml-2 fas fa-times revokeAccess"></i></span>`;
            });
        }
        $("#usersWithAccess").empty().append(html);
    });
});

$("#modal-projects-access").on("click", ".revokeAccess", function(){
    let badge = $(this).parent(".badge");
    let targetUser = badge.attr("id");
    let x = {...{targetUser: targetUser}, ...projectAccessObj};
    ajaxRequest(globalUrls.settings.users.allowedProjects.revokeAccess, x, (data)=>{
        data = makeToastr(data);
        if(data.state == "error"){
            return false;
        }
        badge.remove();
    })
});

$("#modal-projects-access").on("click", "#renameProject", function(){
    let newNameInput = $("#renameProject-newProjectName");
    let newName = newNameInput.val();
    if(newName == ""){
        makeToastr(JSON.stringify({state: "error", message: "Please add new project name"}));
        newNameInput.focus();
        return false;
    }

    let x = $.extend({
        newName: newName
    },projectAccessObj);

    ajaxRequest(globalUrls.projects.rename, x, (data)=>{
        data = makeToastr(data);
        if(data.state == "error"){
            return false;
        }
        $("#modal-projects-access").modal("toggle");
        loadProjectView();
    });
});
</script>
