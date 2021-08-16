    <!-- Modal -->
<div class="modal fade" id="modal-networks-create" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Network</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-2">
              <b> Name </b>
              <input class="form-control" name="name" />
          </div>
          <div class="mb-2">
              <b> Hosts </b>
              <input class="form-control" id="newNetworkHosts"/>
          </div>
          <hr/>
          <b> Config Key / Values (Optional) </b>
          <div class="alert alert-info">
              You can read more about all the keys <a target="_blank" href="https://github.com/lxc/lxd/blob/master/doc/networks.md">
                  here </a>
          </div>

          <table class="table table-bordered" id="networkConfigTable">
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
                          <button class="btn btn-primary" id="addNetworkKeyRow">
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
    $("#newNetworkHosts").tokenInput(globalUrls.hosts.search.search, {
        queryParam: "hostSearch",
        propertyToSearch: "host",
        tokenValue: "hostId",
        preventDuplicates: false,
        theme: "facebook"
    });

    $("#modal-networks-create").on("hide.bs.modal",  function(){
        $("#modal-networks-create input").val("");
        $("#newNetworkHosts").tokenInput("clear");
        $("#networkConfigTable > tbody > tr").not(":last").remove();
    });

    $("#modal-networks-create").on("click", ".removeRow", function(){
        $(this).parents("tr").remove();
    });

    $("#modal-networks-create").on("click", "#addNetworkKeyRow", function(){
        $("#modal-networks-create #networkConfigTable > tbody > tr:last-child").before(
            `<tr>
                <td><input class='form-control' name='key'/></td>
                <td><input class='form-control' name='value'/></td>
                <td><button class='btn btn-danger removeRow'><i class='fa fa-trash'></i></button></td>
            </tr>`
        );
    });

    $("#modal-networks-create").on("click", "#createPool", function(){
        let hosts = mapObjToSignleDimension($("#newNetworkHosts").tokenInput("get"), "hostId");



        let nameInput = $("#modal-networks-create input[name=name]");
        let name = nameInput.val();


        if(name == ""){
            nameInput.focus();
            makeToastr(JSON.stringify({state: "error", message: "Please input name"}));
            return false;
        } else if(hosts.length == 0){
            $("#newNetworkHosts").focus();
            makeToastr(JSON.stringify({state: "error", message: "Please select atleast one host"}));
            return false;
        }

        let x = {
            hosts: hosts,
            name: name,
            config: {}
        }

        let invalid = false;

        $("#networkConfigTable > tbody > tr").not(":last").each(function(){
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

        ajaxRequest(globalUrls.networks.createNetwork, x, (data)=>{
            data = makeToastr(data);
            if(data.state == "success"){
                $("#modal-networks-create").modal("toggle");
                loadNetworksView();
            }
        });
    });

</script>
