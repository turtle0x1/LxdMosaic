    <!-- Modal -->
<div class="modal fade" id="modal-hosts-editSettings" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-xl modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Host Settings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="min-height: 80vh; max-height: 80vh; overflow-y: scroll;">
          <table class="table table-bordered" id="editHostSettingsTable">
              <thead>
                  <tr>
                      <th style="width: 10%">Setting</th>
                      <th style="width: 10%">Scope</th>
                      <th style="width: 50%">Description</th>
                      <th style="width: 30%">Value</th>
                  </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="edit">Save</button>
      </div>
    </div>
  </div>
</div>
<script>

var editHostSettingsDetailsObj = {
    hostId: null,
    hostAlias: ''
}

$("#modal-hosts-editSettings").on("hide.bs.modal", function(){
    $("#modal-hosts-editSettings #alias").val("");
});

$("#modal-hosts-editSettings").on("shown.bs.modal", function(){
    if(!$.isNumeric(editHostSettingsDetailsObj.hostId)){
        makeToastr(JSON.stringify({state: "error", message: "Developer Fail - Please provide host id"}));
        $("#modal-hosts-editSettings").modal("toggle");
        return false;
    }
    ajaxRequest('/api/Hosts/Settings/GetHostSettingsController/get', currentServer, (data)=>{
        data = makeToastr(data)
        let trs = "";
        $.each(hostLxdSettings, (setting, details)=>{
            let value = details.default;
            let trClass = ""
            if(data.hasOwnProperty(setting)){
                trClass = "table-primary"
                value = data[setting];
            }

            trs += `<tr class="${trClass}" data-setting-key="${setting}">
                <td>${setting}</td>
                <td>${details.scope}</td>
                <td>${details.description}</td>
                <td><input class="form-control changeSettingValue" data-orig-value="${value}" value="${value}"/></td>
            </tr>`
        });
        $("#editHostSettingsTable > tbody").empty().append(trs);
    });
});

$("#modal-hosts-editSettings").on("keyup", ".changeSettingValue", function(){
    let input = $(this)
    let tr = $(this).parents("tr");
    let settingKey = tr.data("settingKey")
    let newVal = input.val();
    let trClass = "";

    if(input.data("origValue") != newVal){
        trClass = "table-primary"
    }
    tr.removeClass("table-primary")
    tr.addClass(trClass)
});

$("#modal-hosts-editSettings").on("click", "#edit", function(){
    let settings = {};

    $("#modal-hosts-editSettings").find(".changeSettingValue").each(function(){
        let tr = $(this).parents("tr");
        let input = $(this)
        let settingKey = tr.data("settingKey")
        let newVal = input.val();
        if(input.data("origValue") != newVal){
            settings[settingKey] = newVal;
        }
    });

    let x = {
        hostId: editHostSettingsDetailsObj.hostId,
        settings: settings
    };

    ajaxRequest('/api/Hosts/Settings/UpdateHostSettingsController/update', x, function(data){
        data = makeToastr(data);
        if(data.state == "error"){
            return false;
        }
        $("#modal-hosts-editSettings").modal("hide");
        //TODO Hacky way to reload the view behind the modal, navigo doesn't
        //     support reloading the current URL (for whatever reason)
        let d = {data: {hostId: editHostSettingsDetailsObj.hostId}}
        loadHostSettings(d)
    });
});

</script>
