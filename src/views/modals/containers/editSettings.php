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
        <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            Currently not possible to delete existing keys.
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle" id="editSettings-list">
                <thead class="table-light">
                    <tr>
                        <th style="width: 30%;">Key</th>
                        <th style="width: 35%;">Description</th>
                        <th style="width: 25%;">Value</th>
                        <th style="width: 10%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <button class="btn btn-success mt-2 text-nowrap" id="addNewSettingRow">
            <i class="fas fa-plus"></i> Add Setting
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
        $("#editSettings-list tbody").empty();
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
                    existingSettingsHtml += `<tr>
                        <td>
                            <select name='key' class='form-select form-select-sm settingSelect' disabled='disabled'>
                                <option value='${item.key}' selected>${item.key}</option>
                            </select>
                        </td>
                        <td class='text-muted small description'>${item.description || '—'}</td>
                        <td>
                            <input type="text" name="value" value="${item.value}" class="form-control form-control-sm"/>
                        </td>
                        <td class="text-center">
                            <small class="text-muted">Read-only</small>
                        </td>
                    </tr>`;
                });
                $("#editSettings-list tbody").empty().append(existingSettingsHtml);
            }else{
                $("#editSettings-list tbody").empty();
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
        $(this).closest("tr").remove();
    });

    $("#modal-container-editSettings").on("change", ".settingSelect", function(){
        $(this).val();
        let defaultValue = $(this).find(":selected").data("default");
        let description = $(this).find(":selected").data("description");
        $(this).closest("tr").find("input[name=value]").val(defaultValue);
        $(this).closest("tr").find(".description").text(description || '—');
    });

    $("#modal-container-editSettings").on("click", "#addSettings", function(){
        let btn = $(this);

        let rows = $("#editSettings-list tbody").find("tr");
        let data = {
            settings: {}
        };
        let invalid = false;
        $.each(rows, function(i, item){
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
                loadInstanceView(currentContainerDetails, true, false);
            }
        });
    });

    $("#modal-container-editSettings").on("click", "#addNewSettingRow", function(){
        $("#editSettings-list tbody").append(
            `<tr>
                <td>
                    <select name='key' class='form-select form-select-sm settingSelect'> ${reamingSettingSelectOptions}</select>
                </td>
                <td class='text-muted small description'></td>
                <td>
                    <input type="text" name="value" class="form-control form-control-sm"/>
                </td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm removeSetting"><i class="fa fa-trash"></i></button>
                </td>
            </tr>`
        );
    });

</script>
