    <!-- Modal -->
<div class="modal fade" id="modal-container-snapshot" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Take Snapshot For Container
            <b>
                <span class="snapshotModal-containerName"></span>
            </b>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info ">
            Snapshots take "pictures" of the current state of your container
        </div>
        <h5>
            <b> Container </b> <span class="snapshotModal-containerName"></span> <br/>
            <b> Host: </b> <span id="snapshotModal-currentHost"></span>
        </h5>
        <div class="form-group">
            <b><label> Snapshot Name </label></b>
            <input class="form-control validateName" maxlength="63" name="" id="snapshotModal-snapshotName" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary takeSnapshot">Take Snapshot</button>
      </div>
    </div>
  </div>
</div>
<script>
    $("#modal-container-snapshot").on("hide.bs.modal", function(){
        $("#snapshotModal-snapshotName").val("");
    });

    $("#modal-container-snapshot").on("shown.bs.modal", function(){

        if(!$.isPlainObject(currentContainerDetails)){
            $("#modal-container-migrate").modal("toggle");
            alert("required variable isn't right");
            return false;
        }else if(typeof currentContainerDetails.container !== "string"){
            $("#modal-container-migrate").modal("toggle");
            alert("container isn't set");
            return false;
        }

        $(".snapshotModal-containerName").html(currentContainerDetails.container);
        $("#snapshotModal-currentHost").html(currentContainerDetails.alias);
    });

    $("#modal-container-snapshot").on("click", ".takeSnapshot", function(){
        let x = $.extend({
            snapshotName: $("#snapshotModal-snapshotName").val()
        }, currentContainerDetails);

        ajaxRequest(globalUrls.instances.snapShots.take, x, function(data){
            let x = makeToastr(data);
            if(x.state == "error"){
                return false;
            }

            $("#snapshotModal-snapshotName").val("");
            $("#modal-container-snapshot").modal("toggle");
            loadContainerViewAfter();
        });
    });
</script>
