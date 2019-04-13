    <!-- Modal -->
<div class="modal fade" id="modal-container-restoreSnapshot" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Restore Snapshot From Container
            <b>
                <span class="restoreSnapshotModal-containerName"></span>
            </b>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <dl class="row">
            <dt class="col-sm-6"> Original Container </dt>
            <dd class="col-sm-6 restoreSnapshotModal-containerName"></dd>
            <dt class="col-sm-6"> Host: </dt>
            <dd class="col-sm-6" id="restoreSnapshotModal-currentHost"></dd>
            <dt class="col-sm-6"> Snapshot Name: </dt>
            <dd class="col-sm-6" id="restoreSnapshotModal-snapshotName"></dd>
        </dl>
        <div class="row">
            <div class="col-md-12">
            <h5 clas="pull-left"><u> Restore To Origin </u></h5>
            <div class="alert alert-danger">
                Restoring a snapshot to an existing container will
                overwrite any changed data
            </div>
            <button class="btn btn-primary restoreSnapToOrigin">
                Restore To Origin
            </button>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-12">
            <h5 clas="pull-left"><u> New Container </u></h5>
            <div class="alert alert-info">
                You can create new containers from a snapshot instaed of
                restoring "on top off" an existing container
            </div>
            <div class="form-group">
                <label> New Container Name </label>
                <input class="form-control" id="modal-container-restoreSnapshot-newName" type="string" />
            </div>
            <button class="btn btn-primary createFromSnapshot">
                Create Container
            </button>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger pull-left deleteSnap">Delete Snapshot</button>
      </div>
    </div>
  </div>
</div>
<script>

    var snapshotDetails = {
        snapshotName: null
    };

    $("#modal-container-restoreSnapshot").on("shown.bs.modal", function(){
        if(!$.isPlainObject(currentContainerDetails)){
            $("#modal-container-migrate").modal("toggle");
            alert("required variable isn't right");
            return false;
        }else if(typeof currentContainerDetails.container !== "string"){
            $("#modal-container-migrate").modal("toggle");
            alert("container isn't set");
            return false;
        }else if(typeof snapshotDetails.snapshotName !== "string"){
            $("#modal-container-migrate").modal("toggle");
            alert("snapshot name isn't set");
            return false;
        }

        $(".restoreSnapshotModal-containerName").html(currentContainerDetails.container);
        $("#restoreSnapshotModal-currentHost").html(currentContainerDetails.alias);
        $("#restoreSnapshotModal-snapshotName").html(snapshotDetails.snapshotName);
    });

    $("#modal-container-restoreSnapshot").on("click", ".createFromSnapshot", function(){
        let x = {
            newContainer: $("#modal-container-restoreSnapshot-newName").val(),
            hostId: currentContainerDetails.hostId,
            container: currentContainerDetails.container + "/" + snapshotDetails.snapshotName
        };

        ajaxRequest(globalUrls.containers.snapShots.createFrom, x, function(data){
            let x = makeToastr(data);
            if(x.hasOwnProperty("error")){
                return false;
            }
            $("#modal-container-restoreSnapshot").modal("toggle");
            loadContainerViewAfter();
        });
    });

    $("#modal-container-restoreSnapshot").on("click", ".restoreSnapToOrigin", function(){
        let x = $.extend(snapshotDetails, currentContainerDetails);
        ajaxRequest(globalUrls.containers.snapShots.restore, x, function(data){
            let x = makeToastr(data);
            if(x.hasOwnProperty("error")){
                return false;
            }
            $("#modal-container-restoreSnapshot").modal("toggle");
        });
    });

    $("#modal-container-restoreSnapshot").on("click", ".deleteSnap", function(){
        let x = $.extend(snapshotDetails, currentContainerDetails);
        ajaxRequest(globalUrls.containers.snapShots.delete, x, function(data){
            let r = makeToastr(data);
            if(r.state == "error"){
                return false;
            }
            $("#modal-container-restoreSnapshot").modal("toggle");
            loadContainerViewAfter();
        });
    });
</script>
