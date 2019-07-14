    <!-- Modal -->
<div class="modal fade" id="modal-container-editSettings" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Settings <b><span class="editSettings-containerName"></span></b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>
            <b> On Host: </b> <span id="editSettings-currentHost"></span>
        </h5>
        <div class="alert alert-warning">
            This uses the update stragery so its currently <b> not possible to
            delete existing keys. </b>
        </div>
        <div id="editSettings-list"></div>
        <br/>
        <button class="btn btn-primary" id="addNewSettingRow">
            Add Setting
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addSettings">Add Settings</button>
      </div>
    </div>
  </div>
</div>
<script>

    var reamingSettingSelectOptions = "";

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

        ajaxRequest(globalUrls.containers.getCurrentSettings, currentContainerDetails, function(data){
            data = $.parseJSON(data);
            if(data.existingSettings.length > 0){
                let existingSettingsHtml = "";
                $.each(data.existingSettings, function(i, item){
                    existingSettingsHtml += `<div style='margin-bottom: 5px; border-bottom: 1px solid black; padding: 10px;' class='input-group'>
                    <div class='col-md-4'>
                        <div class='input-group-prepend'>
                            <select name='key' class='form-control settingSelect' disabled='disabled' style='width: 100%'>
                                <option value='${item.key}' selected>${item.key}</option>
                             </select>
                            </div>
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
            }

            if(!$.isEmptyObject(data.remainingSettings)){
                reamingSettingSelectOptions += "<option value=''>Please Select</option>";
                $.each(data.remainingSettings, function(i, item){
                    reamingSettingSelectOptions += `<option
                        data-default='${item.value}'
                        data-description='${item.description}'
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

        data = $.extend(data, currentContainerDetails);
        ajaxRequest(globalUrls.containers.setSettings, data, function(data){
            data = makeToastr(data);
            if(data.state == "success"){
                $("#modal-container-editSettings").modal("toggle");
            }
        });
    });

    $("#modal-container-editSettings").on("click", "#addNewSettingRow", function(){
        $("#editSettings-list").append(
            `<div style='margin-bottom: 5px; border-bottom: 1px solid black; padding: 10px;' class='input-group'>
            <div class='col-md-4'>
                <div class='input-group-prepend'>
                    <select name='key' class='form-control settingSelect' style='width: 100%'> ${reamingSettingSelectOptions}</select>
                    </div>
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
