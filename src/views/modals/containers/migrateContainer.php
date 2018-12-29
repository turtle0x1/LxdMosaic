    <!-- Modal -->
<div class="modal fade" id="modal-container-migrate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Migrate Container <b><span class="migrateModal-containerName"></span></b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger ">
            This falty at best (avoid using this if possible)
        </div>
        <div class="alert alert-info ">
            Migrating a container will <b> move </b> it from one host to another
        </div>
        <h5>
            <b> Moving </b> <span class="migrateModal-containerName"></span> <br/>
            <b> From Host: </b> <span id="migrateModal-currentHost"></span>
        </h5>
        <div class="form-group">
            <b><label> To Host </label></b>
            <select class="form-control" name="" id="migrateModal-targetHost">
                <option value="">Todo Load Hosts</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary migrate">Migrate</button>
      </div>
    </div>
  </div>
</div>
<script>
    $("#modal-container-migrate").on("shown.bs.modal", function(){
        if(!$.isPlainObject(currentContainerDetails)){
            $("#modal-container-migrate").modal("toggle");
            alert("required variable isn't right");
            return false;
        }else if(typeof currentContainerDetails.container !== "string"){
            $("#modal-container-migrate").modal("toggle");
            alert("container isn't set");
            return false;
        }
        $(".migrateModal-containerName").html(currentContainerDetails.container);
        $("#migrateModal-currentHost").html(currentContainerDetails.host);

        ajaxRequest(globalUrls.hosts.getAllHosts, {}, function(data){
            let result = $.parseJSON(data);
            let html = "";
            $.each(result, function(i, item){
                html += "<option value='" + item + "'>" + item + "</option>'"
            });
            $("#migrateModal-targetHost").empty().append(html);
        });

    });

    $("#modal-container-migrate").on("click", ".migrate", function(){
        let x = $.extend({
            destination: $("#migrateModal-targetHost").val()
        }, currentContainerDetails);

        ajaxRequest(globalUrls.containers.migrate, x, function(data){
            console.log(data);
        });
    });
</script>
