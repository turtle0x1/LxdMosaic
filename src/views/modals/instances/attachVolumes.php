    <!-- Modal -->
<div class="modal fade" id="modal-container-attachVolumes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-database me-2"></i>Attach Volume to <span id="volumeContainerName"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light">
          <div class="form-group">
              <label>Volume</label>
              <select class="form-control" name="volume"></select>
          </div>
          <div class="form-group">
              <label>Device Name</label>
              <input class="form-control" name="name" placeholder="dataDisk" />
          </div>
          <div class="form-group">
              <label>Instance Path</label>
              <input class="form-control" name="path" placeholder="/opt/my/data" />
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="attachVolumes" class="btn btn-primary">Attach Volume</button>
      </div>
    </div>
  </div>
</div>
<script>
    $("#modal-container-attachVolumes").on("hide.bs.modal", function(){
        $("#modal-container-attachVolumes").find(":input").not(":button").empty();
        $("#modal-container-attachVolumes").find(":input").not(":button").val("");
    });

    $("#modal-container-attachVolumes").on("click", "#attachVolumes", function(){
        let x = {};
        let failed = false;

        $("#modal-container-attachVolumes").find(":input").not(":button").each(function(){
            let name = $(this).attr("name");
            let value = $(this).val();
            if(name == "volume"){
                value =  $(this).find(":selected").data();
                if(Object.keys(value).length == 0){
                    makeToastr({state: "error", message: "Please select volume"});
                    failed = true;
                    return false;
                }
            }else if(value == ""){
                $(this).focus();
                makeToastr({state: "error", message: "Please input " + name});
                failed = true;
                return false;
            }

            x[name] = value;
        });

        if(failed){
            return false;
        }

        x = {...x, ...currentContainerDetails};

        ajaxRequest(globalUrls.instances.volumes.assign, x, (response)=>{
            response = makeToastr(response);
            if(response.state == "error"){
                return false;
            }
            $("#modal-container-attachVolumes").modal("hide");
            loadContainerViewAfter();
        });
    });

    $("#modal-container-attachVolumes").on("show.bs.modal", function(){
        if(!$.isPlainObject(currentContainerDetails)){
            $("#modal-container-migrate").modal("toggle");
            alert("required variable isn't right");
            return false;
        }else if(typeof currentContainerDetails.container !== "string"){
            $("#modal-container-migrate").modal("toggle");
            alert("container isn't set");
            return false;
        }

        $("#volumeContainerName").text(currentContainerDetails.container)

        ajaxRequest(globalUrls.storage.volumes.getOnHost, currentContainerDetails, (data)=>{
            data = makeToastr(data);
            let volumesSelect = "";
            volumesSelect = "<option value=''>Please select</option>";
            $.each(data, (_, volume)=>{
                volumesSelect += `<option data-pool="${volume.pool}" data-name="${volume.name}" data-project="${volume.project}">${volume.name}</option>`;
            });
            $("#modal-container-attachVolumes").find("select[name=volume]").empty().append(volumesSelect);
        });
    });
</script>
