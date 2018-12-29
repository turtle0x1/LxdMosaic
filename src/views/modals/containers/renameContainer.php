    <!-- Modal -->
<div class="modal fade" id="modal-container-rename" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Rename Container <b><span class="renameModal-containerName"></span></b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>
            <b> Renaming </b> <span class="renameModal-containerName"></span> <br/>
            <b> From Host: </b> <span id="renameModal-currentHost"></span>
        </h5>
        <div class="form-group">
            <b><label> New Name</label></b>return
            <input class="form-control validateName" maxlength="63" id="renameModal-newName"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary rename">Rename</button>
      </div>
    </div>
  </div>
</div>
<script>
    $("#modal-container-rename").on("shown.bs.modal", function(){
        if(!$.isPlainObject(currentContainerDetails)){
            $("#modal-container-migrate").modal("toggle");
            alert("required variable isn't right");
            return false;
        }else if(typeof currentContainerDetails.container !== "string"){
            $("#modal-container-migrate").modal("toggle");
            alert("container isn't set");
            return false;
        }
        $(".renameModal-containerName").html(currentContainerDetails.container);
        $("#renameModal-currentHost").html(currentContainerDetails.host);
    });

    $("#modal-container-rename").on("click", ".rename", function(){
        let x = $.extend({
            newContainer: $("#renameModal-newName").val()
        }, currentContainerDetails);

        ajaxRequest(globalUrls.containers.rename, x, function(data){
            let x = makeToastr(data);
            if(x.hasOwnProperty("error")){
                return false;
            }
            $("#modal-container-rename").modal("toggle");
        });
    });
</script>
