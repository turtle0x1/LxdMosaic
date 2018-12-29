    <!-- Modal -->
<div class="modal fade" id="modal-container-copy" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Copy Container <b><span class="copyModal-containerName"></span></b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>
            <b> Copying </b> <span class="copyModal-containerName"></span> <br/>
            <b> From Host: </b> <span id="copyModal-currentHost"></span>
        </h5>
        <div class="form-group">
            <b><label>New Container Name</label></b>
            <input class="form-control validateName" maxlength="63" id="copyModal-newName"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary copy">Copy</button>
      </div>
    </div>
  </div>
</div>
<script>
    $("#modal-container-copy").on("shown.bs.modal", function(){
        if(!$.isPlainObject(currentContainerDetails)){
            $("#modal-container-migrate").modal("toggle");
            alert("required variable isn't right");
            return false;
        }else if(typeof currentContainerDetails.container !== "string"){
            $("#modal-container-migrate").modal("toggle");
            alert("container isn't set");
            return false;
        }
        $(".copyModal-containerName").html(currentContainerDetails.container);
        $("#copyModal-currentHost").html(currentContainerDetails.host);
    });

    $("#modal-container-copy").on("click", ".copy", function(){
        let x = $.extend({
            newContainer: $("#copyModal-newName").val()
        }, currentContainerDetails);

        ajaxRequest(globalUrls.containers.copy, x, function(data){
            let x = makeToastr(data);
            if(x.hasOwnProperty("error")){
                return false;
            }
            $("#modal-container-copy").modal("toggle");
        });
    });
</script>
