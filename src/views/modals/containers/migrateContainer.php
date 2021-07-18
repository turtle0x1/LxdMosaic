    <!-- Modal -->
<div class="modal fade" id="modal-container-migrate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-people-carry me-2"></i>Migrating Instance <b><span class="migrateModal-containerName"></span></b></h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
            <i class="fas fa-info-circle text-warning me-2"></i>Migrating an instance is a copy followed by a <b>delete</b>
        </div>
        <b>Origin: </b> <span id="migrateModal-currentHost"></span>
        <div class="form-group">
            <b><label>Destination</label></b>
            <select class="form-control" name="" id="migrateModal-targetHost">
                <option value="">Todo Load Hosts</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary migrate">Migrate</button>
      </div>
    </div>
  </div>
</div>
<script>
    $("#migrateModal-targetHost").tokenInput(globalUrls.hosts.search.search, {
        queryParam: "hostSearch",
        propertyToSearch: "host",
        tokenValue: "hostId",
        preventDuplicates: false,
        tokenLimit: 1,
        theme: "facebook"
    });

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
        $("#migrateModal-currentHost").html(currentContainerDetails.alias);
    });

    $("#modal-container-migrate").on("click", ".migrate", function(){
        let targetHost = $("#migrateModal-targetHost").tokenInput("get");

        if(targetHost.length == 0){
            $.alert("Please select target host");
            return false;
        }

        let btn = $(this);
        btn.html(`<i class="fas fa-cog fa-spin me-2"></i>Migrating`)
        $("#modal-container-migrate").find("button").attr("disabled", true);
        let x = $.extend({
            destination: targetHost[0].hostId
        }, currentContainerDetails);

        ajaxRequest(globalUrls.instances.migrate, x, function(data){
            makeToastr(data);
            btn.text(`Migrate`)
            $("#modal-container-migrate").find("button").attr("disabled", false);
            if(data.hasOwnProperty("state") && data.state == "error"){
                return false;
            }
            $("#modal-container-migrate").modal("hide");
        });
    });
</script>
