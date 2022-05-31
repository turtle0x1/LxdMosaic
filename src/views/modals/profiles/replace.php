<!-- Modal -->
<div class="modal fade" id="modal-profile-edit" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Profile <b><span class="renameProfile-profileName"></span></b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <ul class="list-group" id="editProfileStepList">
                            <li style="cursor: pointer" class="list-group-item active">1. Basic Details</li>
                            <li style="cursor: pointer" class="list-group-item">2. Config (Optional)</li>
                            <li style="cursor: pointer" class="list-group-item">3. Devices (Optional)</li>
                        </ul>
                    </div>
                    <div class="col-md-9" style="max-height: 57vh; min-height: 57vh; overflow-y: scroll; border-left: 1px solid black;">
                        <div class="editProfileBox" data-step="1">
                            <div class="mb-2">
                                <b> Host </b>
                                <div id="renameProfile-host"></div>
                            </div>
                            <div class="mb-2">
                                <b> Profile Name </b>
                                <input class="form-control" id="replaceProfileName" disabled/>
                            </div>
                            <div class="mb-2">
                                <b> Description (Optional) </b>
                                <textarea class="form-control" id="replaceProfileDescription"></textarea>
                            </div>
                        </div>
                        <div class="editProfileBox pt-2" data-step="2" style="display: none">
                            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                                <b>Profile Config (Optional)</b>
                                <button class="btn btn-outline-primary btn-sm" id="addReplaceProfileSettingRow">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <table class="table table-bordered" id="replaceProfileSettingTable">
                                <thead>
                                    <tr>
                                        <th>Key</th>
                                        <th>Value</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="editProfileBox pt-2" data-step="3" style="display: none">
                            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                                <b>Profile Devices (Optional)</b>
                                <button class="btn btn-outline-primary btn-sm" id="addReplaceProfileDevice">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="list-group" id="editProfileDeviceObjects">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="renameProfile">Update</button>
            </div>
        </div>
    </div>
</div>
<script>
    var editProfileData = {
        hostAlias: null,
        currentName: null,
        hostId: null
    }

    var editProfilesTableRow = null;

    function changeEditProfileBox(newIndex){
        $(".editProfileBox").hide();
        $(`.editProfileBox[data-step='${(newIndex)}']`).show();
        $("#editProfileStepList").find(".active").removeClass("active");
        $("#editProfileStepList").find(`li:eq(${newIndex - 1})`).addClass("active");
    }

    function _createDeviceHtml(device){

        let editDisabled = ["nic", "proxy"].includes(device.type) ? "disabled" : ""

        let editButton = ""
        if(editDisabled == "disabled"){
            editButton = `<span class="" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Device type ${device.type} not supported yet">`
        }

        editButton += `<button class="btn btn-sm btn-outline-primary editProfileDevice h-100 ${editDisabled}" ${editDisabled}>
          <i class="fas fa-edit"></i>
        </button>
        `
        if(editDisabled == "disabled"){
            editButton += `</span>`
        }

        let html = `<div href="#" class="list-group-item list-group-item-action text-dark" aria-current="true" data-device='${JSON.stringify(device)}'>
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1">${device.name}</h5>
          <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
              <div class="btn-group me-2" role="group" aria-label="First group">
                  ${editButton}
                  <button class="btn btn-sm btn-outline-danger removeProfileDevice">
                    <i class="fas fa-trash"></i>
                  </button>
              </div>
          </div>
        </div>
        <p class="mb-1">${device.type}</p>`

        $.each(device.properties, (key, value)=>{
            html += `<div><small><b>${key}:</b> ${value}</small></div>`
        })

        html += `</div>`
        return html;
    }

    $("#modal-profile-edit").on("click", "#editProfileStepList li", function(){
        changeEditProfileBox($(this).index() + 1);
    });

    $("#modal-profile-edit").on("hide.bs.modal", function() {
        $("#modal-profile-edit input").val("");
        $("#editProfileDeviceObjects, #replaceProfileSettingTable > tbody").empty()
    });

    $("#modal-profile-edit").on("click", "#addReplaceProfileSettingRow", function(){
        $("#replaceProfileSettingTable > tbody").append(editProfilesTableRow);
    });

    $("#modal-profile-edit").on("shown.bs.modal", function() {
        if (editProfileData.hostAlias == null) {
            makeToastr(JSON.stringify({
                state: "error",
                message: "Developer fail - Please provide host alias"
            }));
            return false;
        } else if (editProfileData.currentName == null) {
            makeToastr(JSON.stringify({
                state: "error",
                message: "Developer fail - Please provide currentName"
            }));
            return false;
        }
        let options = "";
        $.each(lxdProfileKeys, (_, item)=>{
            options += `<option value='${item}'>${item}</option>`
        });
        editProfilesTableRow = `<tr>
            <td><select class='form-select' name="key">${options}</select></td>
            <td><input class='form-control' name="value"/></td>
            <td><button class='btn btn-outline-danger deleteProfileSettingRow'><i class="fas fa-trash"></i></button></td>
        </tr>`

        ajaxRequest(globalUrls.profiles.getProfile, {hostId: editProfileData.hostId, profile: editProfileData.currentName}, (data)=>{
            data = makeToastr(data)
            $("#replaceProfileDescription").val(data.description)

            $.each(data.config, (key, value)=>{
                if(!lxdProfileKeys.includes(key)){
                    return true;
                }

                let tr = $(editProfilesTableRow)

                tr.find(".form-select option[value='" + key + "']").attr("selected", "selected");
                tr.find("input[name=value]").val(value);

                $("#replaceProfileSettingTable > tbody").append(tr)
            })

            let html = ""

            $.each(data.devices, (name, properties)=>{
                let t = properties.type
                delete properties["type"]

                let device = {
                    name: name,
                    type: t,
                    properties: properties
                }

                html += _createDeviceHtml(device)
            })
            $("#editProfileDeviceObjects").append(html)
            $("#editProfileDeviceObjects").find('[data-bs-toggle="tooltip"]').tooltip({html: true})
        });

        $("#replaceProfileName").val(editProfileData.currentName);
        $("#renameProfile-host").text(editProfileData.hostAlias);
    });

    $("#modal-profile-edit").on("click", ".removeProfileDevice", function(){
        $(this).parents(".list-group-item").remove()
    });

    $("#modal-profile-edit").on("click", "#addReplaceProfileDevice", function(){
        newDeviceHelperObj.callback = function(device){
            let html = `<div href="#" class="list-group-item list-group-item-action text-dark" aria-current="true" data-device='${JSON.stringify(device)}'>
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1">${device.name}</h5>
              <button class="btn btn-sm btn-outline-danger removeProfileDevice">
                <i class="fas fa-trash"></i>
              </button>
            </div>
            <p class="mb-1">${device.type}</p>`

            $.each(device.properties, (key, value)=>{
                html += `<div><small><b>${key}:</b> ${value}</small></div>`
            })

            html += `</div>`
            $("#editProfileDeviceObjects").append(html)
        }
        $("#modal-helpers-newDeviceObj").modal("show")
    })


    $("#modal-profile-edit").on("click", ".editProfileDevice", function() {
        let listGroupItem = $(this).parents(".list-group-item")
        let device = listGroupItem.data().device
        editDeviceHelperObj.mosaicDevice = device
        editDeviceHelperObj.callback = function (device){
            listGroupItem.replaceWith(_createDeviceHtml(device))
        }
        $("#modal-helpers-editDeviceObj").modal("show")
    });

    $("#modal-profile-edit").on("click", "#renameProfile", function() {
        let description = $("#replaceProfileDescription").val();
        let invalid = false;
        let config = {};

        $("#replaceProfileSettingTable > tbody > tr").each(function(){
            let tr = $(this);
            let keyInput = tr.find("select[name=key]");
            let key = keyInput.val().trim();
            let valueInput = tr.find("input[name=value]")
            let value = valueInput.val().trim();

            if(key == ""){
                keyInput.focus()
                invalid = true;
                return false;
            }else if(value == ""){
                valueInput.focus()
                invalid = true;
                return false;
            }

            config[key] = value;
        });

        if(invalid){
            makeToastr(JSON.stringify({state: "error", message: "Check profile config values"}));
            changeCreateProfileBox(2)
            return false;
        }

        let devices = {}

        $("#editProfileDeviceObjects .list-group-item").each(function(){
            let device = $(this).data().device

            devices[device.name] = {
                type: device.type,
                ...device.properties
            }
        });

        let x = {
            hostId: editProfileData.hostId,
            name: editProfileData.currentName,
            description: description,
            config: config,
            devices: devices
        };

        ajaxRequest('/api/Profiles/ReplaceProfileController/replace', x, (data) => {
            data = makeToastr(data);
            if (data.state == "success") {
                $("#modal-profile-edit").modal("toggle");
                viewProfile(editProfileData.currentName, hostsAliasesLookupTable[editProfileData.hostId], editProfileData.hostId)
            }
        });
    });
</script>
