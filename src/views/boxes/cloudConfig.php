<div id="cloudConfigBox" class="boxSlide">
<div id="cloudConfigOverview" class="row">
    <div class="col-md-12">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h4>Cloud Config</h4>
        <div class="btn-toolbar float-end">
          <div class="btn-group me-2">
              <button data-toggle="tooltip" data-placement="bottom" title="Create Cloud Config" class="btn btn-block btn-primary" id="createCloudConfig">
                  <i class="fa fa-plus"></i>
              </button>
          </div>
        </div>
    </div>
    </div>
</div>
<div  id="cloudConfigContents">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1></h1>
        <div class="btn-toolbar float-end">
          <div class="btn-group me-2">
              <button data-toggle="tooltip" data-placement="bottom" title="Save Cloud Config" class="btn btn-success save">
                  <i class="fas fa-save"></i>
              </button>
              <hr/>
              <button data-toggle="tooltip" data-placement="bottom" title="Deploy Cloud Config" class="btn btn-primary" id="deployCloudConfig">
                  <i class="fas fa-play" style="color: white !important;"></i>
              </button>
              <button data-toggle="tooltip" data-placement="bottom" title="Delete Cloud Config" class="btn btn-danger" id="deleteCloudConfig">
                  <i class="fas fa-trash"></i>
              </button>
          </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-dark text-white">
                  <div class="card-header bg-dark" role="tab" >
                    <h5>
                      <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
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
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="card bg-dark text-white">
                  <div class="card-header bg-dark" role="tab" id="cloudConfig-actionsHeading">
                    <h5>
                      <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#cloudConfig-editorCollapse" aria-expanded="true" aria-controls="cloudConfig-editorCollapse">
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
                <div class="card bg-dark text-white">
                  <div class="card-header bg-dark" role="tab">
                    <h5>
                      <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#cloudConfig-envVariablesCollapse" aria-expanded="true" aria-controls="cloudConfig-envVariablesCollapse">
                        Enviroment Variables
                        <i class="fas float-end fa-file"></i>
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

function loadCloudConfigView(cloudConfigId)
{
    let data = {
        id: cloudConfigId
    };

    currentCloudConfigId = cloudConfigId;

    ajaxRequest(globalUrls.cloudConfig.getDetails, data ,function(data){

        let config = $.parseJSON(data);
        $("#cloudConfigImage").tokenInput("clear");

        if(config.data.imageDetails.hasOwnProperty("description")){
            $("#cloudConfigImage").tokenInput("add", config.data.imageDetails);
        }


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
    });
}

function loadCloudConfigTree()
{
    ajaxRequest(globalUrls.cloudConfig.getAll, null, function(data){
        loadCloudConfigOverview();
        var data = $.parseJSON(data);
        let hosts = `
        <li class="mb-2 cloudConfig-overview">
            <a class="" style="text-decoration: none;" href="#">
                <i class="fas fa-tachometer-alt mr-5"></i> Overview
            </a>
        </li>`;
        let currentId = "a";
        $.each(data, function(i, item){

            hosts += `<li class="mb-2">
                <a class="d-inline href="#">
                    <i class="fas fa-server me-2"></i>${i}
                </a>
                <button class="btn  btn-outline-secondary btn-sm btn-toggle align-items-center rounded d-inline float-end me-2" data-bs-toggle="collapse" data-bs-target="#${currentId}" aria-expanded="true">
                    <i class="fas fa-caret-down"></i>
                </button>
                <div class=" mt-2 bg-dark text-white" id="${currentId}">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small hostInstancesUl" style="display: inline;">`

            $.each(item, function(o, z){
                hosts += `<li class="nav-item view-cloudConifg"
                    data-id="${z.id}"
                    data-name="${z.name}"
                    data-namespace="${i}">
                  <a class="nav-link" href="#">
                    <i class="nav-icon fa fa-cog"></i>
                    ${z.name}
                  </a>
                </li>`;
            });
            hosts += `</ul></div></li>`
            currentId = nextLetter(currentId)

        });

        $("#sidebar-ul").empty().append(hosts);
    });
}

$("#sidebar-ul").on("click", ".view-cloudConifg", function(){
    addBreadcrumbs([$(this).data("namespace"), $(this).data("name")], ["", "active"]);
    loadCloudConfigView($(this).data("id"));
});

function loadCloudConfigOverview(){
    $(".boxSlide, #cloudConfigContents").hide();
    $("#cloudConfigBox, #cloudConfigOverview").show();
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
                            loadCloudConfigTree();
                        }
                    });
                }
            }
        }
    });
});
</script>

<?php
    require __DIR__ . "/../modals/cloudConfig/create.php";
    require __DIR__ . "/../modals/cloudConfig/deploy.php";
?>
