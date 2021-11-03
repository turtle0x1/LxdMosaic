<!-- Modal -->
<div class="modal fade" id="modal-hosts-instnaces-addProxyDevice" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
  <div class="modal-header bg-primary text-white">
    <h5 class="modal-title " id="exampleModalLongTitle"><i class="fas fa-exchange-alt pe-2"></i>Add Proxy Device</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
      <div class="input-group">
          <span class="input-group-text">Name</span>
          <input type="text" placeholder="My Proxy" id="proxyName" class="form-control" />
      </div>
      <div class="input-group">
          <span class="input-group-text">Source</span>
          <input type="text" placeholder="tcp:0.0.0.0:5000" id="proxySource" class="form-control" />
      </div>
      <div class="input-group">
          <span class="input-group-text">Destination</span>
          <input type="text" placeholder="tcp:0.0.0.0:80" id="proxyDestination" class="form-control" />
      </div>
      <div class="input-group" id="proxyDeviceInstanceInputGroup">
          <span class="input-group-text">Instance</span>
          <select class="form-select" id="proxyDeviceInstance">
          </select>
      </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" id="addProxyDeviceBtn">Add</button>
  </div>
</div>
</div>
</div>
<script>
    var addProxyDeviceObj = {
        hostId: null,
        instance: null
    }

    $("#modal-hosts-instnaces-addProxyDevice").on("hide.bs.modal", function(){
        $("#modal-hosts-instnaces-addProxyDevice input").val("");
        $("#proxyDeviceInstance").empty();
        addProxyDeviceObj.hostId = null
        addProxyDeviceObj.instance = null
    });

    $("#modal-hosts-instnaces-addProxyDevice").on("show.bs.modal", function(){
        if(!$.isNumeric(addProxyDeviceObj.hostId)){
            makeToastr(JSON.stringify({state: "error", message: "Developer Fail - Please provide host id"}));
            $("#modal-hosts-instnaces-addProxyDevice").modal("toggle");
            return false;
        }

        if(addProxyDeviceObj.instance == null){
            ajaxRequest(globalUrls.hosts.instances.getHostContainers, addProxyDeviceObj, (data)=>{
                data = makeToastr(data);
                let html = "";
                $.each(data, (container, details)=>{
                    html += `<option value=${container}>
                        ${container}
                    </option>`
                });
                $("#proxyDeviceInstanceInputGroup").show()
                $("#proxyDeviceInstance").empty().html(html);
            });
        }else{
            $("#proxyDeviceInstanceInputGroup").hide()
        }
    });

    $("#modal-hosts-instnaces-addProxyDevice").on("click", "#addProxyDeviceBtn", function(){
        function _enableButton(btn) {
            btn.attr("disabled", false)
            btn.html(`Add`)
        }
        let btn = $(this);
        btn.attr("disabled", true)
        btn.html(`<i class="fas fa-cog fa-spin me-2"></i>Add`)
        let source = $("#proxySource").val();
        let instance = $("#proxyDeviceInstance").val()
        let destination = $("#proxyDestination").val();
        let name = $("#proxyName").val();

        if(addProxyDeviceObj.instance !== null){
            instance = addProxyDeviceObj.instance
        }

        if(source == ""){
            $("#proxySource").focus();
            makeToastr({state: "error", message: "Please provide source"});
            _enableButton(btn)
            return false;
        }else if(destination == ""){
            $("#proxyDestination").focus();
            makeToastr({state: "error", message: "Please provide destination"});
            _enableButton(btn)
            return false;

        }else if(name == ""){
            $("#proxyName").focus();
            makeToastr({state: "error", message: "Please provide name"});
            _enableButton(btn)
            return false;

        }else if(instance == ""){
            $("#proxyDeviceInstance").focus();
            makeToastr({state: "error", message: "Please select an instance"});
            _enableButton(btn)
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
                if(addProxyDeviceObj.instance !== null){
                    loadContainerView(currentContainerDetails);
                }else{
                    $("#serverProxyDevicesBtn").trigger("click")
                }
                $("#modal-hosts-instnaces-addProxyDevice").modal("hide");
            }
            _enableButton(btn)
        });

    });

</script>
