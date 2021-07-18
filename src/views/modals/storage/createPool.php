    <!-- Modal -->
<div class="modal fade" id="modal-storage-createPool" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Project</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <b> Name </b>
              <input class="form-control" name="name" />
          </div>
          <div class="form-group">
              <b> Size (GB) </b>
              <input class="form-control" name="sizeInGb" />
          </div>
          <div class="form-group">
              <b> Driver </b>
              <select class="form-control" name="driver" id="newStorageDriver">
                  <option value="">Please Select </option>
                  <option value="zfs">ZFS</option>
                  <option value="Btrfs">BTRFS</option>
                  <option value="LVM">LVM</option>
                  <option value="CEPH">CEPH</option>
                  <option value="dir">DIR</option>
              </select>
          </div>
          <div class="form-group">
              <b> Hosts </b>
              <input class="form-control" id="newStoragePoolHosts"/>
          </div>
          <hr/>
          <b> Extra Keys (Optional) </b>
          <div class="alert alert-info">
              You can read more about all the keys <a target="_blank" href="https://github.com/lxc/lxd/blob/master/doc/storage.md">
                  here </a>
          </div>

          <table class="table table-bordered" id="storageConfigTable">
              <thead>
                  <tr>
                      <th> Key </th>
                      <th> Value </th>
                      <th> Remove </th>
                  </tr>
              </thead>
              <tbody>
                  <tr class='addBtnRow'>
                      <td colspan="3" class="text-center">
                          <button class="btn btn-primary" id="addStorageKeyRow">
                              Add Key/Value
                          </button>
                      </td>
                </tr>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="createPool">Create</button>
      </div>
    </div>
  </div>
</div>
<script>
    $("#newStoragePoolHosts").tokenInput(globalUrls.hosts.search.search, {
        queryParam: "hostSearch",
        propertyToSearch: "host",
        tokenValue: "hostId",
        preventDuplicates: false,
        theme: "facebook"
    });

    $("#modal-storage-createPool").on("hide.bs.modal",  function(){
        $("#modal-storage-createPool input, #modal-storage-createPool textarea").val("");
        $("#storageConfigTable > tbody > tr").not(":last").remove();
        $("#newStoragePoolHosts").tokenInput("clear");
        $("#newStorageDriver").val("")
    });

    $("#modal-storage-createPool").on("click", ".removeRow", function(){
        $(this).parents("tr").remove();
    });

    $("#modal-storage-createPool").on("click", "#addStorageKeyRow", function(){
        $("#modal-storage-createPool #storageConfigTable > tbody > tr:last-child").before(
            `<tr>
                <td><input class='form-control' name='key'/></td>
                <td><input class='form-control' name='value'/></td>
                <td><button class='btn btn-danger removeRow'><i class='fa fa-trash'></i></button></td>
            </tr>`
        );
    });

    $("#modal-storage-createPool").on("click", "#createPool", function(){
        let hosts = mapObjToSignleDimension($("#newStoragePoolHosts").tokenInput("get"), "hostId");



        let nameInput = $("#modal-storage-createPool input[name=name]");
        let sizeInGbInput = $("#modal-storage-createPool input[name=sizeInGb]");
        let driverInput = $("#modal-storage-createPool select[name=driver]");
        let name = nameInput.val();
        let sizeInGb = sizeInGbInput.val();
        let driver = driverInput.val();

        if(name == ""){
            nameInput.focus();
            makeToastr(JSON.stringify({state: "error", message: "Please input name"}));
            return false;
        } else if(sizeInGb == "" || !$.isNumeric(sizeInGb)){
            sizeInGbInput.focus();
            makeToastr(JSON.stringify({state: "error", message: "Please input size in gb"}));
            return false;
        } else if(driver == ""){
            driverInput.focus();
            makeToastr(JSON.stringify({state: "error", message: "Please select driver"}));
            return false;
        } else if(hosts.length == 0){
            $("#newStoragePoolHosts").focus();
            makeToastr(JSON.stringify({state: "error", message: "Please select atleast one host"}));
            return false;
        }

        let x = {
            hosts: hosts,
            name: name,
            driver: driver,
            config: {
                "size": sizeInGb + "GB"
            }
        }

        let invalid = false;

        $("#storageConfigTable > tbody > tr").not(":last").each(function(){
            let tr = $(this);
            let keyInput = tr.find("input[name=key]");
            let valueInput = tr.find("input[name=value]");
            let key = keyInput.val();
            let value = valueInput.val();
            if(key == ""){
                keyInput.focus();
                makeToastr(JSON.stringify({state: "error", message: "Please input key"}));
                return false;
            } else if(value == ""){
                valueInput.focus();
                makeToastr(JSON.stringify({state: "error", message: "Please input value"}));
                return false;
            }

            x.config[key] = value;
        });

        if(invalid){
            return false;
        }


        ajaxRequest(globalUrls.storage.createPool, x, (data)=>{
            data = makeToastr(data);
            if(data.state == "success"){
                $("#modal-storage-createPool").modal("toggle");
                loadStorageView();
            }
        });
    });

</script>
