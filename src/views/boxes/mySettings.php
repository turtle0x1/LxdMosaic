<div id="mySettingsBox" class="boxSlide">
    <div id="settingsOverview" class="mySettingBox">
        <div class="row">
            <div class="col-md-6">
                  <div class="card bg-dark text-white">
                  <div class="card-header d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center" role="tab" >
                      <h5>
                        <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#currentSettingsTable" aria-expanded="true" aria-controls="currentSettingsTable">
                            My Permanent API keys
                        </a>
                        </h5>
                        <div class="btn-toolbar float-end">
                            <div class="btn-group me-2">
                                <button class="btn btn-success" id="createToken">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="" class="collapse in show" role="tabpanel" >
                      <div class="card-body bg-dark">
                        <table class="table table-dark table-bordered" id="apiKeyTable">
                            <thead>
                                <tr>
                                    <th>Date Created</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
            </div>
         </div>
     </div>
</div>


<script>

// Bad this is a global but saves 2 extra api's
var allInstanceTypes = {};

var currentProvider;

function loadMySettings()
{
    setBreadcrumb("My Settings", "viewMySettings active");
    $(".sidebar-fixed").addClass("sidebar-lg-show");
    changeActiveNav(null)
    $("#myAccountBtn").addClass("active");
    
    $(".boxSlide").hide();
    $(".mySettingBox").hide();
    $("#settingsOverview, #mySettingsBox").show();
    $(".sidebar-fixed").addClass("sidebar-lg-show");

    allInstanceTypes = {};
    currentProvider = null;

    let hosts = `
    <li class="mt-2 my-settings">
        <a class="text-info" href="#">
            <i class="fas fa-user me-2"></i>My API Keys
        </a>
    </li>`


    $("#sidebar-ul").empty().append(hosts);

    addBreadcrumbs(["My Settings", "API Keys"], ["viewMySettings", "active"], false);

    ajaxRequest(globalUrls.settings.getMyOverview, {}, (data)=>{
        data = makeToastr(data);
        if(data.hasOwnProperty("state") && data.state == "error"){
            return false;
        }

        let trs = "";

        if(data.permanentTokens.length == 0){
            trs = "<tr><td colspan='999' class='text-center'><i class='fas fa-info-circle text-success me-2'></i>No Permanent API Keys!</td></tr>"
        }else{
            $.each(data.permanentTokens, (_, token)=>{
                trs += `<tr>
                    <td>${moment.utc(token.created).local().format("llll")}</td>
                    <td><button class="btn btn-danger btn-sm deleteToken" id="${token.id}" ><i class="fas fa-trash"></i></button></td>
                </tr>`;
            });
        }

        $("#apiKeyTable > tbody").empty().append(trs);
    });

}

$("#settingsOverview").on("click", "#createToken", function(){
    $.confirm({
        title: 'Create Permanent API Key!',
        content: `
        <div class="mb-2">
            <label>Token</label>
            <input class="form-control" name="token"/>
        </div>
        `,
        buttons: {
            cancel: function () {
                //close
            },
            formSubmit: {
                text: 'Create',
                btnClass: 'btn-blue',
                action: function () {
                    var token = this.$content.find('input[name=token]').val().trim();
                    if(token == ""){
                        $.alert('Please provide a token');
                        return false;
                    }
                    let x = {token: token};
                    ajaxRequest(globalUrls.user.tokens.create, x, (response)=>{
                        response = makeToastr(response);
                        if(response.state == "error"){
                            return false;
                        }
                        tr.remove();
                    });
                }
            }
        },
        onContentReady: function () {
            // bind to events
            var jc = this;
            this.$content.find('input[name=token]').val(Math.random().toString(36).substring(2));
        }
    });
});

$("#settingsOverview").on("click", ".deleteToken", function(){
    let tr = $(this).parents("tr");
    let x = {tokenId: $(this).attr("id")};
    ajaxRequest(globalUrls.user.tokens.delete, x, (response)=>{
        response = makeToastr(response);
        if(response.state == "error"){
            return false;
        }
        tr.remove();
    });
});
</script>
