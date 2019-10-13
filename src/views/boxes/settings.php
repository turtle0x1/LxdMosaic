<div id="settingsBox" class="boxSlide">
    <div id="settingsOverview" class="row">
        <div class="col-md-10">
              <div class="card">
                <div class="card-header bg-info" role="tab" id="headingOne">
                  <h5>
                    <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                      Settings
                    </a>
                  </h5>
                </div>
                <div id="cloudConfigDescription" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
                  <div class="card-block bg-dark">
                    <table class="table table-dark table-bordered" id="settingListTable">
                        <thead>
                            <tr>
                                <th>Setting</th>
                                <th>Description</th>
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
        <div class="col-md-2">
              <div class="card">
                <div class="card-header bg-info" role="tab" id="headingOne">
                  <h5>
                    <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Actions
                    </a>
                  </h5>
                </div>
                <div id="collapseOne" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
                  <div class="card-block bg-dark">
                      <button class="btn btn-block btn-primary" id="saveSettings">
                          Save
                      </button>
                  </div>
                </div>
              </div>
        </div>
    </div>
</div>

<script>

function loadSettingsView()
{
    $(".boxSlide").hide();
    $("#settingsOverview, #settingsBox").show();
    $(".sidebar-lg-show").removeClass("sidebar-lg-show");
    setBreadcrumb("Settings", "viewSettings active");

    ajaxRequest(globalUrls.settings.getAll, {}, (data)=>{
        data = makeToastr(data);
        let trs = "";
        $.each(data, function(_, item){
            trs += `<tr data-setting-id="${item.settingId}">
                    <td>${item.settingName}</td>
                    <td>${item.settingDescription}</td>
                    <td><input class="form-control" name="settingValue" value="${item.currentValue}"/></td>
                </tr>`
        });
        $("#settingListTable > tbody").empty().append(trs);
    });
}

$("#settingsOverview").on("click", "#saveSettings", function(){
    let settings = [];
    $("#settingListTable > tbody > tr").each(function(){
        let tr = $(this);

        settings.push({
            id: tr.data("settingId"),
            value: tr.find("input[name=settingValue]").val()
        });
    });
    ajaxRequest(globalUrls.settings.saveAll, {settings: settings}, (data)=>{
        makeToastr(data)
    });
});
</script>
