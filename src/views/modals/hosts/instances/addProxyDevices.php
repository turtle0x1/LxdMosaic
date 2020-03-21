<!-- Modal -->
<div class="modal fade" id="modal-hosts-instnaces-addProxyDevice" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLongTitle">Add Proxy Devices</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
      <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">Name</span>
          </div>
          <input type="text" placeholder="My Proxy" id="proxyName" class="form-control" />
      </div>
      <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">Source</span>
          </div>
          <input type="text" placeholder="tcp:0.0.0.0:5000" id="proxySource" class="form-control" />
      </div>
      <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">Destination</span>
          </div>
          <input type="text" placeholder="tcp:0.0.0.0:80" id="proxyDestination" class="form-control" />
      </div>
      <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">Instance</span>
          </div>
          <select class="form-control" id="proxyDeviceInstance">
          </select>
      </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" id="addProxyDeviceBtn">Add</button>
  </div>
</div>
</div>
</div>
<script>
    var addProxyDeviceObj = {
        hostId: null
    }
    $("#modal-hosts-instnaces-addProxyDevice").on("shown.bs.modal", function(){
        if(!$.isNumeric(addProxyDeviceObj.hostId)){
            makeToastr(JSON.stringify({state: "error", message: "Developer Fail - Please provide host id"}));
            $("#modal-hosts-instnaces-addProxyDevice").modal("toggle");
            return false;
        }

        ajaxRequest(globalUrls.hosts.containers.getHostContainers, addProxyDeviceObj, (data)=>{
            data = makeToastr(data);
            let html = "";
            $.each(data, (container, details)=>{
                html += `<option value=${container}>
                    ${container}
                </option>`
            });
            $("#proxyDeviceInstance").empty().html(html);
        });
    });

    $("#modal-hosts-instnaces-addProxyDevice").on("click", "#addProxyDeviceBtn", function(){
        let source = $("#proxySource").val();
        let instance = $("#proxyDeviceInstance").val()
        let destination = $("#proxyDestination").val();
        let name = $("#proxyName").val();

        if(source == ""){
            $("#proxySource").focus();
            makeToastr({state: "error", message: "Please provide source"});
            return false;
        }else if(destination == ""){
            $("#proxyDestination").focus();
            makeToastr({state: "error", message: "Please provide destination"});
            return false;

        }else if(name == ""){
            $("#proxyName").focus();
            makeToastr({state: "error", message: "Please provide name"});
            return false;

        }else if(instance == ""){
            $("#proxyDeviceInstance").focus();
            makeToastr({state: "error", message: "Please select an instance"});
            return false;
        }

        let x = {
            hostId: addProxyDeviceObj.hostId,
            instance: instance,
            name: name,
            source: source,
            destination: destination
        }

        ajaxRequest(globalUrls.hosts.instances.addProxyDevice, x, (data)=>{
            data = makeToastr(data);
            if(data.hasOwnProperty("state") && data.state == "success"){
                $("#modal-hosts-instnaces-addProxyDevice").modal("hide");
                $("#serverProxyDevicesBtn").trigger("click")
            }
        });

    });

</script>
