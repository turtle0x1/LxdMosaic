<!-- Modal -->
<div class="modal fade" id="modal-storage-createVolume" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Create Volume</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="row mb-2">
              <div class="col-md-6">
                  <b> Name </b>
                  <input class="form-control" name="name" />
              </div>
              <div class="col-md-6">
                  <b> Size (GB) </b>
                  <input class="form-control" name="sizeInGb" />
              </div>
          </div>
          <div>
              <b> Configuration (Optional) </b>
              <i class="fas fa-info-circle text-info ms-2 me-1"></i>You can read more about all the keys <a target="_blank" class="link-primary" href="https://github.com/lxc/lxd/blob/master/doc/storage.md">
                  here </a>
          </div>

          <table class="table table-bordered" id="storageVolumeConfigTable">
              <thead>
                  <tr>
                      <th> Key </th>
                      <th> Value </th>
                  </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="createVolume">Create</button>
      </div>
    </div>
  </div>
</div>
<script>
    $("#newStorageVolumeHosts").tokenInput(globalUrls.hosts.search.search, {
        queryParam: "hostSearch",
        propertyToSearch: "host",
        tokenValue: "hostId",
        preventDuplicates: false,
        theme: "facebook"
    });

    var createVolumeObj = {
        pool: null,
        hostId: null,
        driver: null
    }

    $("#modal-storage-createVolume").on("show.bs.modal",  function(){
        let allKeys = {
            "all": {
                "security.shifted": "false",
                "security.unmapped": "false",
                "snapshots.expiry": "-",
                "snapshots.schedule": "-",
                "snapshots.pattern": "snap%d"
            },
            "block": {
                "block.filesystem": "",
                "block.mount_options": ""
            },
            "zfs": {
                "zfs.remove_snapshots": "",
                "zfs.use_refquota": ""
            },
            "lvm" : {
                "lvm.stripes": "",
                "lvm.stripes.size": ""
            }
        }


        let keysToUse = ["all"];

        if(allKeys.hasOwnProperty(createVolumeObj.driver)){
            keysToUse.push(createVolumeObj.driver)
        }

        let trs = "";

        $.each(keysToUse, (_, key)=>{
            $.each(allKeys[key], (config, value)=>{
                trs += `<tr data-key="${config}">
                    <td>${config}</td>
                    <td><input class="form-control" name="value" placeholder="${value}"/></td>
                </tr>`
            });
        });

        $("#storageVolumeConfigTable > tbody").empty().append(trs);
    });

    $("#modal-storage-createVolume").on("hide.bs.modal",  function(){
        $("#modal-storage-createVolume input").val("");
        $("#storageVolumeConfigTable > tbody > tr").remove();
    });

    $("#modal-storage-createVolume").on("click", ".removeRow", function(){
        $(this).parents("tr").remove();
    });

    $("#modal-storage-createVolume").on("click", "#createVolume", function(){
        let nameInput = $("#modal-storage-createVolume input[name=name]");
        let sizeInGbInput = $("#modal-storage-createVolume input[name=sizeInGb]");

        let name = nameInput.val();
        let sizeInGb = sizeInGbInput.val();

        if(name == ""){
            nameInput.focus();
            makeToastr(JSON.stringify({state: "error", message: "Please input name"}));
            return false;
        } else if(sizeInGb == "" || !$.isNumeric(sizeInGb)){
            sizeInGbInput.focus();
            makeToastr(JSON.stringify({state: "error", message: "Please input size in gb"}));
            return false;
        }

        let x = {
            pool: createVolumeObj.pool,
            hostId: createVolumeObj.hostId,
            name: name,
            config: {
                "size": sizeInGb + "GB"
            }
        }

        let invalid = false;

        $("#storageVolumeConfigTable > tbody > tr").each(function(){
            let tr = $(this);
            let key = tr.data("key")
            x.config[key] = tr.find("input[name=value]").val();
        });

        if(invalid){
            return false;
        }

        ajaxRequest(globalUrls.storage.volumes.create, x, (data)=>{
            data = makeToastr(data);
            if(data.state == "success"){
                $("#modal-storage-createVolume").modal("toggle");
                loadStorageView();
            }
        });
    });

</script>
