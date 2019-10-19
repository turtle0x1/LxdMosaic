<div id="cloudConfigBox" class="boxSlide">
<div id="cloudConfigOverview" class="row">
    <div class="col-md-9">
          <div class="card">
            <div class="card-header bg-info" role="tab" >
              <h5>
                <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                  Cloud Config
                </a>
              </h5>
            </div>
            <div id="cloudConfigDescription" class="collapse in show" role="tabpanel" >
              <div class="card-block bg-dark">
                  Cloud config files are scripts or controllers that run when
                  a container is first created to set it up for a particular task.
              </div>
            </div>
          </div>
    </div>
    <div class="col-md-3">
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
                  <button class="btn btn-block btn-primary" id="createCloudConfig">
                      Create
                  </button>
              </div>
            </div>
          </div>
    </div>
</div>
<div class="row" id="cloudConfigContents">
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                  <div class="card-header bg-info" role="tab" >
                    <h5>
                      <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Image
                        <i class="fas float-right fa-image"></i>
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
                <div class="card">
                  <div class="card-header bg-info" role="tab" id="cloudConfig-actionsHeading">
                    <h5>
                      <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#cloudConfig-editorCollapse" aria-expanded="true" aria-controls="cloudConfig-editorCollapse">
                        Cloud Config File
                        <i class="fas float-right fa-file"></i>
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
                <div class="card">
                  <div class="card-header bg-info" role="tab">
                    <h5>
                      <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#cloudConfig-envVariablesCollapse" aria-expanded="true" aria-controls="cloudConfig-envVariablesCollapse">
                        Enviroment Variables
                        <i class="fas float-right fa-file"></i>
                      </a>
                    </h5>
                  </div>
                  <div class="bg-dark" id="cloudConfig-envVariablesCollapse" class="collapse show" role="tabpanel" aria-labelledby="cloudConfig-actionsHeading">
                    <div class="card-block bg-dark">
                        <button class="btn btn-primary float-right mb-2" id="addEnvVariableRow">
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
    <div class="col-md-2">
          <div class="card">
            <div class="card-header bg-info" role="tab" >
              <h5>
                <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Actions
                </a>
              </h5>
            </div>
            <div class="bg-dark" id="collapseOne" class="collapse in show" role="tabpanel" >
              <div class="card-block">
                  <button class="btn btn-block btn-success save">
                      Save
                  </button>
                  <hr/>
                  <button class="btn btn-block btn-primary" id="deployCloudConfig">
                      Deploy
                  </button>
                  <button class="btn btn-block btn-danger" id="deleteCloudConfig">
                      Delete
                  </button>
              </div>
            </div>
          </div>
    </div>
</div>
</div>
<script>

var currentCloudConfigId = null;

$("#cloudConfigImage").tokenInput(globalUrls.images.search.searchAllHosts, {
    queryParam: "image",
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
        <li class="nav-item active cloudConfig-overview">
            <a class="nav-link" href="#">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>`;
        $.each(data, function(i, item){
            hosts += `<li class="nav-item nav-dropdown open">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon fas fa-caret-down"></i> ${i}
                </a>
                <ul class="nav-dropdown-items">`;

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
            hosts += "</ul></li>";
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

        x.envVariables[key] = val;
    });

    if(invalid){
        return false;
    }

    ajaxRequest(globalUrls.cloudConfig.update, x, function(response){
        response = makeToastr(response);
        if(response.hasOwnProperty("error")){
            return false;
        }
    });
});

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
