<div id="settingsBox" class="boxSlide">
    <div id="settingsOverview" class="row">
        <div class="col-md-10">
              <div class="card">
                <div class="card-header bg-info" role="tab" >
                  <h5>
                    <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#currentSettingsTable" aria-expanded="true" aria-controls="currentSettingsTable">
                      Current Instance Settings
                    </a>
                  </h5>
                </div>
                <div id="currentSettingsTable" class="collapse in show" role="tabpanel" >
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
              <div class="card">
                <div class="card-header bg-info" role="tab" >
                  <h5>
                    <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#lastRecordedActions" aria-expanded="true" aria-controls="lastRecordedActions">
                      Showing Last <span id="actionCount"></span> Recorded Actions
                    </a>
                    <button class="btn btn-warning float-right" id="loadMoreRecordedActions">
                        Load More
                    </button>
                  </h5>
                </div>
                <div id="lastRecordedActions" class="collapse in show" role="tabpanel">
                  <div class="card-block bg-dark">
                    <table class="table table-dark table-bordered" id="recordedActionsTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Controller</th>
                                <th>Params</th>
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
                <div class="card-header bg-info" role="tab" >
                  <h5>
                    <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Actions
                    </a>
                  </h5>
                </div>
                <div id="collapseOne" class="collapse in show" role="tabpanel" >
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
    loadRecordedActions();
}

function loadRecordedActions(ammount = 30){
    $("#actionCount").text(ammount);
    ajaxRequest(globalUrls.settings.recordedActions.getLastResults, {ammount: ammount}, (data)=>{
        data = $.parseJSON(data);
        let trs = "";
        if(data.length > 0 ){
            $.each(data, (_, item)=>{
                trs += `<tr>
                    <td>${moment(item.date).fromNow()}</td>
                    <td>${item.controller}</td>
                    <td>${item.params}</td>
                </tr>`;
            });
        }else{
            trs += `<tr><td colspan="999" class="text-info">No Recorded Actions</td></tr>`
        }
        $("#recordedActionsTable > tbody").empty().append(trs);
    });
}

$("#settingsOverview").on("click", "#loadMoreRecordedActions", function(){
    $.confirm({
    title: 'Prompt!',
    content: `
    <div class="alert alert-danger">
        Loading to many logs may crash your browser!
    </div>
    <form action="" class="formName">
        <div class="form-group">
            <label>Ammount Of Logs To Fetch</label>
            <input type="text" value="30" class="ammount form-control" required />
        </div>
    </form>`,
    buttons: {
        formSubmit: {
            text: 'Submit',
            btnClass: 'btn-blue',
            action: function () {
                var ammount = this.$content.find('.ammount').val();
                if(!$.isNumeric(ammount)){
                    $.alert('provide a valid ammount');
                    return false;
                }
                loadRecordedActions(ammount)
            }
        },
        cancel: function () {
            //close
        },
    },
    onContentReady: function () {
        // bind to events
        var jc = this;
        this.$content.find('form').on('submit', function (e) {
            // if the user submits the form by pressing enter in the field.
            e.preventDefault();
            jc.$$formSubmit.trigger('click'); // reference the button and click it
        });
    }
});
});

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
