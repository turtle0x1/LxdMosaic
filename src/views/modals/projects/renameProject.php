    <!-- Modal -->
<div class="modal fade" id="modal-projects-rename" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Rename Project</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-2">
              <b> New Name </b>
              <input class="form-control" id="renameProject-newProjectName"/>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="renameProject">Rename</button>
      </div>
    </div>
  </div>
</div>
<script>
var renameProjectObj = {
    hostId: null,
    project: null
}

$("#modal-projects-rename").on("hide.bs.modal",  function(){
    $("#modal-projects-rename input").val("");
});

$("#modal-projects-rename").on("shown.bs.modal", function(){
    if(renameProjectObj.hostId == null || renameProjectObj.hostId == ""){
        $("#modal-projects-rename").modal("toggle");
        alert("current hostId isn't set");
        return false;
    }else if(renameProjectObj.project == null || renameProjectObj.project == ""){
        $("#modal-projects-rename").modal("toggle");
        alert("current project isn't set");
        return false;
    }
});

$("#modal-projects-rename").on("click", "#renameProject", function(){
    let newNameInput = $("#renameProject-newProjectName");
    let newName = newNameInput.val();
    if(newName == ""){
        makeToastr(JSON.stringify({state: "error", message: "Please add new project name"}));
        newNameInput.focus();
        return false;
    }

    let x = $.extend({
        newName: newName
    },renameProjectObj);

    ajaxRequest(globalUrls.projects.rename, x, (data)=>{
        data = makeToastr(data);
        if(data.state == "error"){
            return false;
        }
        $("#modal-projects-rename").modal("toggle");
        router.navigate(`/projects/${hostIdOrAliasForUrl(renameProjectObj.hostId, hostsAliasesLookupTable[renameProjectObj.hostId])}/${newName}`)
    });
});
</script>
