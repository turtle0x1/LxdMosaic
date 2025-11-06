<div id="cloudConfigBox" class="boxSlide">
<div id="cloudConfigOverview" class="row">
    <div class="col-md-12">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h4>Cloud Config</h4>
        <div class="btn-toolbar float-end">
          <div class="btn-group me-2">
              <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create Cloud Config" class="btn btn-outline-primary p-1" id="createCloudConfig">
                  <i class="fa fa-plus"></i>
              </button>
          </div>
        </div>
    </div>
    </div>
</div>
<div  id="cloudConfigContents">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 id="cloudConfigIdName"></h1>
        <div class="btn-toolbar float-end">
          <div class="btn-group me-2">
              <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Save Cloud Config" class="btn btn-success save">
                  <i class="fas fa-save"></i>
              </button>
              <hr/>
              <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Deploy Cloud Config" class="btn btn-primary" id="deployCloudConfig">
                  <i class="fas fa-play" style="color: white !important;"></i>
              </button>
              <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete Cloud Config" class="btn btn-danger" id="deleteCloudConfig">
                  <i class="fas fa-trash"></i>
              </button>
          </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-7">
                <div class="card bg-dark text-white">
                  <div class="card-header bg-dark" role="tab" id="cloudConfig-actionsHeading">
                    <h5>
                      <a class="text-white">
                        Cloud Config File
                        <i class="fas float-end fa-file"></i>
                      </a>
                    </h5>
                  </div>
                  <div class="bg-dark" id="cloudConfig-editorCollapse" class="collapse show" role="tabpanel" aria-labelledby="cloudConfig-actionsHeading">
                    <div class="card-block bg-dark">
                        <div id="editor"></div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card mb-2 bg-dark text-white">
                  <div class="card-header bg-dark" role="tab" >
                    <h5>
                      <a class="text-white">
                        Image
                        <i class="fas float-end fa-image"></i>
                      </a>
                    </h5>
                  </div>
                  <div id="collapseOne" class="collapse in show" role="tabpanel" >
                    <div class="card-body bg-dark">
                        <input class="form-control" id="cloudConfigImage"/>
                    </div>
                  </div>
                </div>
                <div class="card bg-dark text-white">
                  <div class="card-header bg-dark" role="tab">
                    <h5>
                      <a class="text-white">
                        Environment Variables
                        <i class="fas fa-user-cog float-end "></i>
                      </a>
                    </h5>
                  </div>
                  <div class="bg-dark" id="cloudConfig-envVariablesCollapse" class="collapse show" role="tabpanel" aria-labelledby="cloudConfig-actionsHeading">
                    <div class="card-block bg-dark">
                        <button class="btn btn-primary float-end mb-2" id="addEnvVariableRow">
                            <i class="fas fa-plus"></i>
                        </button>
                        <table class="table table-bordered table-dark text-white" id="cloudConfigEnvTable">
                            <thead>
                                <tr>
                                    <th> Key </th>
                                    <th> Value </th>
                                    <th> Delete </th>
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
</div>
<script>

var currentCloudConfigId = null;

$("#cloudConfigImage").tokenInput(globalUrls.images.search.searchAllHosts, {
    queryParam: "search",
    tokenLimit: 1,
    propertyToSearch: "description",
    theme: "facebook",
    tokenValue: "details"
});

var envVariableTr = `<tr>
    <td><input class="form-control" name="key"/></td>
    <td><input class="form-control" name="value"/></td>
    <td><button class="btn btn-danger deleteEnvRow"><i class="fas fa-trash-alt"></i></button></td>
</tr>
`;

function loadCloudConfigView(req)
{
    currentCloudConfigId = req.data.id;

    let data = {
        id: req.data.id
    };
    changeActiveNav(".viewCloudConfigFiles")

    loadCloudConfigTree()

    ajaxRequest(globalUrls.cloudConfig.getDetails, data ,function(data){

        let config = makeToastr(data);
        $("#cloudConfigImage").tokenInput("clear");

        if(config.data.imageDetails.hasOwnProperty("description")){
            $("#cloudConfigImage").tokenInput("add", config.data.imageDetails);
        }

        addBreadcrumbs(["Cloud Config", config.header.namespace, config.header.name], ["", "", "active"], false, ["/cloudConfig"])

        $("#cloudConfigContents #cloudConfigIdName").text(config.header.name)

        $("#cloudConfigEnvTable > tbody").empty();
        $.each(config.data.envVariables, (key, val)=>{
            let p = $(envVariableTr);
            p.find("input[name=key]").val(key);
            p.find("input[name=value]").val(val);
            $("#cloudConfigEnvTable > tbody ").append(p);
        });

        editor.setValue(config.data.data, -1);
        $("#cloudConfigOverview").hide();
        $("#cloudConfigContents").show();
        $("#cloudConfigBox").show();
        router.updatePageLinks()
    });
}

function loadCloudConfigTree(force = false)
{
    if(force || $("#sidebar-ul").find("[id^=cloudConfig]").length == 0){
        ajaxRequest(globalUrls.cloudConfig.getAll, null, function(data){
            var data = makeToastr(data);
            let a = currentCloudConfigId == null ? 'active' : '';
            let hosts = `
            <li class="mb-2 mt-2 nav-item">
                <a class="nav-link p-0 ${a}" style="text-decoration: none;" href="/cloudConfig" data-navigo>
                    <i class="fas fa-tachometer-alt mr-5"></i> Overview
                </a>
            </li>`;
            let id = 0;
            $.each(data, function(i, item){

                hosts += `<li class="mb-2">
                    <a class="d-inline">
                        <i class="fas fa-layer-group me-2"></i>${i}
                    </a>
                    <button class="btn btn-outline-secondary btn-sm btn-toggle align-items-center rounded d-inline float-end me-2 toggleDropdown" data-bs-toggle="collapse" data-bs-target="#cloudConfig-namespace-${id}" aria-expanded="true">
                        <i class="fas fa-caret-down"></i>
                    </button>
                    <div class=" mt-2 bg-dark text-white show" id="cloudConfig-namespace-${id}">
                        <ul class="btn-toggle-nav list-unstyled cloudConfigNamespaceGroup fw-normal pb-1" style="display: inline;">`

                $.each(item, function(o, z){
                    let a = z.id == currentCloudConfigId ? "active" : "";
                    hosts += `<li class="nav-item"
                        data-id="${z.id}"
                        data-name="${z.name}"
                        data-namespace="${i}">
                      <a class="nav-link ${a}" href="/cloudConfig/${z.id}" data-navigo>
                        <i class="nav-icon fa fa-file-alt"></i>
                        ${z.name}
                      </a>
                    </li>`;
                });
                id++
                hosts += `</ul></div></li>`
            });

            $("#sidebar-ul").empty().append(hosts);
            router.updatePageLinks()
        });
    }else{
        $("#sidebar-ul").find(".active").removeClass("active");
        if($.isNumeric(currentCloudConfigId)){
            $("#sidebar-ul").find("[data-id='" + currentCloudConfigId + "'] > .nav-link").addClass("active")
        }else{
            $("#sidebar-ul").find(".nav-link:eq(0)").addClass("active")
        }
    }
}

function loadCloudConfigOverview(req){
    if(req.data == null || !req.data.hasOwnProperty("id")){
        currentCloudConfigId = null
    }
    $(".boxSlide, #cloudConfigContents").hide();
    $("#cloudConfigBox, #cloudConfigOverview").show();
    setBreadcrumb("Cloud Config", "active", "/cloudConfig");
    $(".sidebar-fixed").addClass("sidebar-lg-show");
    changeActiveNav(".viewCloudConfigFiles")
    loadCloudConfigTree()
}

$("#cloudConfigBox").on("click", ".deleteEnvRow", function(){
    $(this).parents("tr").remove();
});
$("#cloudConfigBox").on("click", "#addEnvVariableRow", function(){
    $("#cloudConfigEnvTable").append(envVariableTr);
});

$("#cloudConfigBox").on("click", "#createCloudConfig", function(){
    $("#modal-cloudConfig-create").modal("show");
});

$("#cloudConfigBox").on("click", "#deployCloudConfig", function(){
    deployCloudConfigObj.cloudConfigId = parseInt(currentCloudConfigId);
    $("#modal-cloudConfig-deploy").modal("show");
});

$("#cloudConfigBox").on("click", ".save", function(){
    var code = editor.getValue();
    let x = {
        code: code,
        cloudConfigId: currentCloudConfigId,
        envVariables: {}
    }

    let image = $("#cloudConfigImage").tokenInput("get");

    if(image.length == 0){
        makeToastr(JSON.stringify({state: "error", message: "Please select image"}));
        return false;
    }

    x.imageDetails = image[0];

    let invalid = false;
    let doesntStartWithEnvWarning = false;
    $("#cloudConfigEnvTable > tbody > tr").each(function(){
        let tr = $(this);
        let keyInput = tr.find("input[name=key]");
        let valInput = tr.find("input[name=value]");
        let key = keyInput.val();
        let val = valInput.val();
        if(key == ""){
            $.alert("Please input key");
            keyInput.focus();
            invalid = true;
            return false;
        } else if(val == ""){
            $.alert("Please input value");
            keyInput.focus();
            invalid = true;
            return false;
        }

        if(key.startsWith("environment.") !== true){
            doesntStartWithEnvWarning = true;
        }

        x.envVariables[key] = val;
    });

    if(invalid){
        return false;
    }
    if(doesntStartWithEnvWarning){
        $.confirm({
            title: `Key Doesn't Start With enviroment.`,
            content: `The key should probably start with "enviroment." to have the intened effect`,
            buttons: {
                cancel: {
                    btnClass: "btn btn-primary",
                    text: "go back"
                },
                ok: {
                    btnClass: "btn btn-danger",
                    text: "Continue Anyway",
                    action: function(){
                        sendCloduConfigUpdate(x);
                    }
                }
            }
        });
    }else{
        sendCloduConfigUpdate(x);
    }

});

function sendCloduConfigUpdate(cloudConfig){
    ajaxRequest(globalUrls.cloudConfig.update, cloudConfig, function(response){
        response = makeToastr(response);
        if(response.hasOwnProperty("error")){
            return false;
        }
    });
}

$("#cloudConfigBox").on("click", "#deleteCloudConfig", function(){
    $.confirm({
        title: 'STOP - Delete Cloud Config',
        content: 'Are you sure you want to delete this cloud config ? Make sure you have a backup!',
        buttons: {
            cancel: function () {},
            delete: {
                btnClass: 'btn-danger',
                action: function () {
                    ajaxRequest(globalUrls.cloudConfig.delete, {cloudConfigId: currentCloudConfigId}, function(data){
                        let r = makeToastr(data);
                        if(r.state == "success"){
                            let sidebarItem = $("#sidebar-ul").find(`[href^='/cloudConfig/${currentCloudConfigId}']`).parent()
                            let parentUl = sidebarItem.parents(".cloudConfigNamespaceGroup");
                            sidebarItem.remove();
                            if(parentUl.find("li").length == 0){
                                parentUl.parents("li").remove()
                            }
                            router.navigate("/cloudConfig")
                        }
                    });
                }
            }
        }
    });
});
</script>

<?php
require __DIR__ . '/../modals/cloudConfig/create.php';
require __DIR__ . '/../modals/cloudConfig/deploy.php';
?>
