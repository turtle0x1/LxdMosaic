<div class="modal fade" id="modal-storage-createPool">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-hdd me-2"></i>Create Storage Pool</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="min-height: 60vh; max-height: 60vh;">
          <div class="row">
              <div class="col-md-3">
                  <ul class="list-group" id="createStoragePoolStepList">
                      <li style="cursor: pointer" class="list-group-item active">1. Basic Details</li>
                      <li style="cursor: pointer" class="list-group-item">2. Config (Optional)</li>
                  </ul>
              </div>
              <div class="col-md-9" style="max-height: 57vh; min-height: 57vh; overflow-y: scroll; border-left: 1px solid black;">
                  <div class="createStoragePoolBox" data-step="1">
                      <div class="mb-2">
                          <b> Name </b>
                          <input class="form-control" name="name" autocomplete="off"/>
                      </div>
                      <div class="mb-2">
                          <b> Size (GB) </b>
                          <input class="form-control" name="sizeInGb" autocomplete="off"/>
                      </div>
                      <div class="mb-2">
                          <b> Driver </b>
                          <select class="form-select" name="driver" id="newStorageDriver">
                              <option value="">Please Select </option>
                              <option value="zfs">ZFS</option>
                              <option value="Btrfs">BTRFS</option>
                              <option value="LVM">LVM</option>
                              <option value="CEPH">CEPH</option>
                              <option value="dir">DIR</option>
                          </select>
                      </div>
                      <div class="mb-2">
                          <b> Hosts </b>
                          <input class="form-control" id="newStoragePoolHosts" autocomplete="off"/>
                      </div>
                  </div>
                  <div class="createStoragePoolBox" data-step="2" style="display: none">
                      <b> Extra Keys (Optional) </b>
                      <div>
                          <i class="fas fa-info-circle text-info me-1"></i>
                          You can read more about all the keys
                          <a target="_blank" href="https://github.com/lxc/lxd/blob/master/doc/storage.md">here</a>
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
             </div>
         </div>
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

    $("#modal-storage-createPool").on("click", "#createStoragePoolStepList li", function(){
        changeCreateStoragePoolBox($(this).index() + 1);
    });

    function changeCreateStoragePoolBox(newIndex){
        $(".createStoragePoolBox").hide();
        $(`.createStoragePoolBox[data-step='${(newIndex)}']`).show();
        $("#createStoragePoolStepList").find(".active").removeClass("active");
        $("#createStoragePoolStepList").find(`li:eq(${newIndex - 1})`).addClass("active");
    }

    $("#modal-storage-createPool").on("click", ".removeRow", function(){
        $(this).parents("tr").remove();
    });

    $("#modal-storage-createPool").on("click", "#addStorageKeyRow", function(){
        $("#modal-storage-createPool #storageConfigTable > tbody > tr:last-child").before(
            `<tr>
                <td><input class='form-control' name='key' autocomplete="off"/></td>
                <td><input class='form-control' name='value' autocomplete="off"/></td>
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
            changeCreateStoragePoolBox(1)
            nameInput.focus();
            makeToastr(JSON.stringify({state: "error", message: "Please input name"}));
            return false;
        } else if(sizeInGb == "" || !$.isNumeric(sizeInGb)){
            changeCreateStoragePoolBox(1)
            sizeInGbInput.focus();
            makeToastr(JSON.stringify({state: "error", message: "Please input size in gb"}));
            return false;
        } else if(driver == ""){
            changeCreateStoragePoolBox(1)
            driverInput.focus();
            makeToastr(JSON.stringify({state: "error", message: "Please select driver"}));
            return false;
        } else if(hosts.length == 0){
            changeCreateStoragePoolBox(1)
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
                invalid = keyInput
                makeToastr(JSON.stringify({state: "error", message: "Please input key"}));
                return false;
            } else if(value == ""){
                invalid = valueInput
                makeToastr(JSON.stringify({state: "error", message: "Please input value"}));
                return false;
            }

            x.config[key] = value;
        });

        if(invalid){
            changeCreateStoragePoolBox(2)
            invalid.focus()
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
