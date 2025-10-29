    <!-- Modal -->
<div class="modal fade" id="modal-container-editSettings" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">
            <i class="fas fa-cog me-2"></i>Settings For
            <span class="editSettings-containerName"></span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 text-center">
            <i class="fas fa-info-circle text-warning me-2"></i>
            Currently not possible to delete existing keys.
        </div>
        <div class="d-block" id="editSettings-list"></div>
        <button class="btn btn-success mt-2 float-end" id="addNewSettingRow">
            Add Setting
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addSettings">Save Settings</button>
      </div>
    </div>
  </div>
</div>
<script>

    var reamingSettingSelectOptions = "";

    $("#modal-container-editSettings").on("hide.bs.modal", function(){
        $("#editSettings-currentHost").text("");
        $("#editSettings-list").empty();
    });

    $("#modal-container-editSettings").on("shown.bs.modal", function(){

        reamingSettingSelectOptions = "";

        if(!$.isPlainObject(currentContainerDetails)){
            $("#modal-container-editSettings").modal("toggle");
            alert("required variable isn't right");
            return false;
        }else if(typeof currentContainerDetails.container !== "string"){
            $("#modal-container-editSettings").modal("toggle");
            alert("container isn't set");
            return false;
        }

        ajaxRequest(globalUrls.instances.getCurrentSettings, currentContainerDetails, function(data){
            data = makeToastr(data);
            if(data.existingSettings.length > 0){
                let existingSettingsHtml = "";
                $.each(data.existingSettings, function(i, item){
                    existingSettingsHtml += `<div style='margin-bottom: 5px; border-bottom: 1px solid black; padding: 10px;' class='input-group'>
                    <div class='col-md-4'>
                        <select name='key' class='form-select settingSelect' disabled='disabled' style='width: 100%'>
                            <option value='${item.key}' selected>${item.key}</option>
                         </select>
                    </div>
                    <div class='col-md-4'>
                        <div class='description'>${item.description}</div>
                    </div>
                    <div class='col-md-3'>
                        <input  style="width: 100%" type="text" name="value" value="${item.value}" class="form-control"/>
                    </div>
                    </div>`;
                });
                $("#editSettings-list").empty().append(existingSettingsHtml);
            }else{
                $("#editSettings-list").empty()
            }

            if(!$.isEmptyObject(data.remainingSettings)){
                reamingSettingSelectOptions += "<option value=''>Please Select</option>";
                $.each(data.remainingSettings, function(i, item){
                    reamingSettingSelectOptions += `<option
                        data-default='${item.value}'
                        data-description="${item.description}"
                        value='${item.key}'>
                            ${item.key}
                        </option>`;
                });
            }
        });

        $(".editSettings-containerName").html(currentContainerDetails.container);
        $("#editSettings-currentHost").html(currentContainerDetails.alias);
    });

    $("#modal-container-editSettings").on("click", ".removeSetting", function(){
        $(this).parents(".input-group").remove();
    });

    $("#modal-container-editSettings").on("change", ".settingSelect", function(){
        $(this).val();
        let defaultValue = $(this).find(":selected").data("default");
        let description = $(this).find(":selected").data("description");
        $(this).parents(".input-group").find("input[name=value]").val(defaultValue);
        $(this).parents(".input-group").find("div.description").text(description);
    });

    $("#modal-container-editSettings").on("click", "#addSettings", function(){
        let btn = $(this);

        let inputGroups = $("#editSettings-list").find(".input-group");
        let data = {
            settings: {}
        };
        let invalid = false;
        $.each(inputGroups, function(i, item){
            let keySelector = $(this).find("select[name=key]").find(":selected");
            let key = keySelector.val();

            let valueSelector = $(this).find("input[name=value]");
            let value = valueSelector.val();

            if(key == "" || key == undefined){
                makeToastr(JSON.stringify({state: "error", message: "Please set the key"}));
                keySelector.focus();
                return false;
            }else if (value == "") {
                makeToastr(JSON.stringify({state: "error", message: "Please set a value"}));
                keySelector.focus();
                return false;
            }

            data.settings[key] = value;
        });

        if(invalid){
            return false;
        }

        btn.html(`<i class="fas fa-cog fa-spin me-2"></i>Updating Settings`);
        btn.attr("disabled", true);

        data = $.extend(data, currentContainerDetails);
        ajaxRequest(globalUrls.instances.setSettings, data, function(data){
            data = makeToastr(data);
            btn.html(`Save Settings`);
            btn.attr("disabled", false);
            if(data.state == "success"){
                $("#modal-container-editSettings").modal("toggle");
            }
        });
    });

    $("#modal-container-editSettings").on("click", "#addNewSettingRow", function(){
        $("#editSettings-list").append(
            `<div style='margin-bottom: 5px; border-bottom: 1px solid #D3D3D3; padding: 10px;' class='input-group'>
            <div class='col-md-4'>
                <select name='key' class='form-select settingSelect' style='width: 100%'> ${reamingSettingSelectOptions}</select>
            </div>
            <div class='col-md-4'>
                <div class='description'></div>
            </div>
            <div class='col-md-3'>
                <input  style="width: 100%" type="text" name="value" class="form-control"/>
            </div>
            <div class='col-md-1'>
                <button class="btn btn-danger removeSetting"><i class="fa fa-trash"></i></button>
            </div>
            </div>`
        );
    });

</script>
