<div class="modal fade" id="modal-helpers-newDeviceObj" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Device Object</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="min-height: 70vh; max-height: 70vh; overflow: hidden">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-2">
                            <b>New Device Name</b>
                            <input class="form-control" id="newDeviceName"/>
                        </div>
                        <div class="mb-2">
                            <b>New Device</b>
                            <select class="form-select" id="newDeviceType">
                            </select>
                            <div class="form-text" id="newDeviceTypeDescription">
                                Please select new device.
                            </div>
                        </div>

                        <div class="my-2" id="newDeviceTypeTypeDiv" style="display: none">
                            <b>New Device Type</b>
                            <select class="form-select" id="newDeviceTypeType">
                            </select>
                            <div class="form-text" id="newDeviceTypeTypeDescription" style="display: none">
                                Please select new device type.
                            </div>
                        </div>
                        <b>Properties</b>
                        <div id="propertiesTableDiv" style="max-height: 50vh; overflow-y: scroll; display: none;">
                            <table class="table table-bordered" id="newDeviceProperties">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Description</th>
                                        <th>Required</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="createDeviceObjBtn">Create</button>
            </div>
        </div>
    </div>
</div>
<script>

    var newDeviceHelperObj = {
        callback: null
    }

    function _loadProperties(properties){
        let trs = "";
        $.each(properties, (_, property)=>{
            trs += `<tr data-key="${property.name}" data-required="${property.required}" data-default="${property.default}">
                <td>${property.name}</td>
                <td>${property.description}</td>
                <td>${property.required}</td>
                <td><input value="${property.default}" class="form-control"/></td>
            </tr>`
        });
        $("#newDeviceProperties > tbody").empty().append(trs);
        $("#propertiesTableDiv").show();
    }
    $("#modal-helpers-newDeviceObj").on("hide.bs.modal",  function(){
        _loadProperties([])
        $("#newDeviceTypeTypeDiv, #newDeviceTypeTypeDescription, #propertiesTableDiv").hide();
        $("#newDeviceTypeDescription").text("Please select new device.")
        $("#newDeviceName").val("")
    });
    $("#modal-helpers-newDeviceObj").on("show.bs.modal", function() {
        if(typeof newDeviceHelperObj.callback !== "function"){
            $.alert("Developer fail, please provide callback to this modal")
            $("#modal-helpers-newDeviceObj").modal("hide")
            return false;
        }
        $("#newDeviceName").val("")
        let options = "<option value=''>Please select</option>";
        $.each(Object.keys(lxdDevicesProperties), (_, deviceType) => {
            options += `<option value="${deviceType}">${deviceType}</option>`;
        });
        $("#newDeviceType").empty().append(options);
    });

    $("#modal-helpers-newDeviceObj").on("change", "#newDeviceType", function(){
        let type = $(this).val()
        if(type == ""){
            _loadProperties([])
            $("#newDeviceTypeTypeDiv, #newDeviceTypeTypeDescription, #propertiesTableDiv").hide();
            $("#newDeviceTypeDescription").text("Please select new device.")

            return false;
        }
        let typeDetails = lxdDevicesProperties[$(this).val()]
        $("#newDeviceTypeDescription").text(typeDetails.description)

        if(typeDetails.hasOwnProperty("types")){
            let options = "<option value=''>Please select</option>";
            $.each(Object.keys(typeDetails.types), (_, deviceType) => {
                options += `<option value="${deviceType}">${deviceType}</option>`;
            });
            $("#newDeviceProperties > tbody").empty()
            $("#newDeviceTypeType").empty().append(options);
            $("#newDeviceTypeTypeDiv, #newDeviceTypeTypeDescription").show();
            $("#propertiesTableDiv").hide();
            return false;
        }

        $("#newDeviceTypeTypeDiv, #newDeviceTypeTypeDescription").hide();
        _loadProperties(typeDetails.properties)
    });

    $("#modal-helpers-newDeviceObj").on("change", "#newDeviceTypeType", function(){
        let type = $(this).val()
        if(type == ""){
            $("#newDeviceTypeTypeDescription").text("Please select new device type.")
            _loadProperties([])
            $("#propertiesTableDiv").hide()
            return false;
        }
        let typeDetails = lxdDevicesProperties[$("#newDeviceType").val()]
        let typeTypeDetails = typeDetails.types[$(this).val()]
        $("#newDeviceTypeTypeDescription").text(typeTypeDetails.description)
        _loadProperties(typeTypeDetails.properties)
    })

    $("#modal-helpers-newDeviceObj").on("click", "#createDeviceObjBtn", function(){
        let name = $("#newDeviceName").val()
        let type  = $("#newDeviceType").val()
        let typeType = $("#newDeviceTypeType").val()

        if(name == ""){
            makeToastr(JSON.stringify({state: "error", message: "Please provide device name"}));
            $("#newDeviceName").focus()
            return false;
        }

        if(type == ""){
            makeToastr(JSON.stringify({state: "error", message: "Please select device type"}));
            $("#newDeviceType").focus()
            return false;
        }

        let typeDetails = lxdDevicesProperties[type]
        let properties = {};

        if(typeDetails.hasOwnProperty("types")){
            if(typeType == ""){
                makeToastr(JSON.stringify({state: "error", message: "Please select device type"}));
                return false;
            }
            properties[typeDetails.typeKey] = typeType
        }

        let failed = false;
        let failedKey = false;
        $("#newDeviceProperties > tbody > tr").each(function(){
            let d = $(this).data()

            let v = $(this).find("input:eq(0)").val()

            if(d.required === true && (v === "" || v === "-")){
                failed = $(this).find("input:eq(0)");
                failedKey = d.key
                return false;
            }

            if(d.default === 0 && (parseInt(v) === 0)){
                return true;
            }
            if(d.default === true && v === "true"){
                return true;
            }
            if(d.default === false && v === "false"){
                return true;
            }
            if(v === d.default){
                return true;
            }
            properties[d.key] = v
        });

        if(failed){
            failed.focus()
            makeToastr(JSON.stringify({state: "error", message: `Please fill out ${failedKey}`}));
            return false;
        }
        newDeviceHelperObj.callback({
            name: name,
            type: type,
            properties: properties
        })
        $("#modal-helpers-newDeviceObj").modal("hide");
    });
</script>
