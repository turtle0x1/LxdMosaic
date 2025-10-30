<div class="modal fade" id="modal-helpers-editDeviceObj" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Device Object</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="min-height: 70vh; max-height: 70vh; overflow: hidden">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-2">
                            <b>Device Name</b>
                            <input class="form-control" id="editDeviceName"/>
                        </div>
                        <div class="mb-2">
                            <b>Device</b>
                            <select class="form-select" id="editDeviceType" disabled></select>
                            <div class="form-text" id="editDeviceTypeDescription">
                                Please select new device.
                            </div>
                        </div>

                        <div class="my-2" id="editDeviceTypeTypeDiv" style="display: none">
                            <b>Device Type</b>
                            <select class="form-select" id="editDeviceTypeType" disabled>
                            </select>
                            <div class="form-text" id="editDeviceTypeTypeDescription" style="display: none">
                                Please select new device type.
                            </div>
                        </div>

                        <div id="editDevicePropertiesTableDiv" style="max-height: 42vh; overflow-y: scroll; display: none;">
                            <b>Properties</b>
                            <table class="table table-bordered" id="editDeviceProperties">
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
              <button type="button" class="btn btn-primary" id="editDeviceObjBtn">Update</button>
            </div>
        </div>
    </div>
</div>
<script>

    var editDeviceHelperObj = {
        mosaicDevice: null,
        callback: null
    }

    function _loadEditProperties(properties, currentProperties){
        let trs = "";
        $.each(properties, (_, property)=>{
            trs += `<tr data-key="${property.name}" data-required="${property.required}" data-default="${property.default}">
                <td>${property.name}</td>
                <td>${property.description}</td>
                <td>${property.required}</td>
                <td><input value="${currentProperties.hasOwnProperty(property.name) ? currentProperties[property.name] : property.default}" class="form-control"/></td>
            </tr>`
        });
        $("#editDeviceProperties > tbody").empty().append(trs);
        $("#editDevicePropertiesTableDiv").show();
    }

    $("#modal-helpers-editDeviceObj").on("hide.bs.modal",  function(){
        _loadEditProperties([])
        $("#editDeviceTypeTypeDiv, #editDeviceTypeTypeDescription, #editDevicePropertiesTableDiv").hide();
        $("#editDeviceTypeDescription").text("Please select new device.")
        $("#editDeviceName").val("")
    });

    $("#modal-helpers-editDeviceObj").on("show.bs.modal", function() {
        if(typeof editDeviceHelperObj.callback !== "function"){
            $.alert("Developer fail, please provide callback to this modal")
            $("#modal-helpers-editDeviceObj").modal("hide")
            return false;
        }

        let type = editDeviceHelperObj.mosaicDevice.type
        let typeDetails = lxdDevicesProperties[type]

        $("#editDeviceName").val(editDeviceHelperObj.mosaicDevice.name)
        $("#editDeviceType").empty().append(`<option value="${type}">${type}</option>`);
        $("#editDeviceTypeDescription").text(typeDetails.description)

        if(typeDetails.hasOwnProperty("types")){
            let options = "<option value=''>Please select</option>";
            let deviceTypeType = editDeviceHelperObj.mosaicDevice.properties[typeDetails.typeKey]
            let typeTypeDetails = typeDetails.types[deviceTypeType]
            $("#editDeviceTypeType").empty().append(`<option value="${deviceTypeType}">${deviceTypeType}</option>`);
            $("#editDeviceTypeTypeDiv, #editDeviceTypeTypeDescription").show();
            $("#editDeviceTypeTypeDescription").text(typeTypeDetails.description)
            _loadEditProperties(typeTypeDetails.properties, editDeviceHelperObj.mosaicDevice.properties)
        }else{
            $("#editDeviceTypeTypeDiv, #editDeviceTypeTypeDescription").hide();
            _loadEditProperties(typeDetails.properties, editDeviceHelperObj.mosaicDevice.properties)
        }
    });

    $("#modal-helpers-editDeviceObj").on("click", "#editDeviceObjBtn", function(){
        let name = $("#editDeviceName").val()
        let type  = $("#editDeviceType").val()
        let typeType = $("#editDeviceTypeType").val()

        if(name == ""){
            makeToastr(JSON.stringify({state: "error", message: "Please provide device name"}));
            $("#editDeviceName").focus()
            return false;
        }

        if(type == ""){
            makeToastr(JSON.stringify({state: "error", message: "Please select device"}));
            $("#editDeviceType").focus()
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
        let failedKey = false
        $("#editDeviceProperties > tbody > tr").each(function(){
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
            makeToastr(JSON.stringify({state: "error", message: `Please fill out ${failedKey}`}));
            return false;
        }
        editDeviceHelperObj.callback({
            name: name,
            type: type,
            properties: properties
        })
        $("#modal-helpers-editDeviceObj").modal("hide");
    });
</script>
