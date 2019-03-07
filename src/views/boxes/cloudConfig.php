<div id="cloudConfigBox" class="boxSlide">
<div id="cloudConfigOverview" class="row">
    <div class="col-md-9">
          <div class="card">
            <div class="card-header" role="tab" id="headingOne">
              <h5>
                <a data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                  Cloud Config
                </a>
              </h5>
            </div>
            <div id="cloudConfigDescription" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
              <div class="card-block">
                  Cloud config files are scripts or controllers that run when
                  a container is first created to set it up for a particular task.
              </div>
            </div>
          </div>
    </div>
    <div class="col-md-3">
          <div class="card">
            <div class="card-header" role="tab" id="headingOne">
              <h5>
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Actions
                </a>
              </h5>
            </div>
            <div id="collapseOne" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
              <div class="card-block">
                  <button class="btn btn-block btn-primary" id="createCloudConfig">
                      Create
                  </button>
              </div>
            </div>
          </div>
    </div>
</div>
<div class="row" id="cloudConfigContents">
    <div class="col-md-9">
        <div class="card">
          <div class="card-header" role="tab" id="cloudConfig-actionsHeading">
            <h5>
              <a data-toggle="collapse" data-parent="#accordion" href="#cloudConfig-editorCollapse" aria-expanded="true" aria-controls="cloudConfig-editorCollapse">
                Cloud Config File
              </a>
            </h5>
          </div>
          <div id="cloudConfig-editorCollapse" class="collapse show" role="tabpanel" aria-labelledby="cloudConfig-actionsHeading">
            <div class="card-block">
                <div clas="row">
                    <div id="editor"></div>
                </div>
            </div>
          </div>
        </div>
    </div>
    <div class="col-md-3">
          <div class="card">
            <div class="card-header" role="tab" id="headingOne">
              <h5>
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Actions
                </a>
              </h5>
            </div>
            <div id="collapseOne" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
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

function loadCloudConfigView(cloudConfigId)
{
    let data = {
        id: cloudConfigId
    };

    currentCloudConfigId = cloudConfigId;

    ajaxRequest(globalUrls.cloudConfig.getDetails, data ,function(data){
        let config = $.parseJSON(data);
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
        let h = [{
            text: "Overview",
            icon: "fa fa-home",
            type: "overview",
            state: {
                selected: true
            }
        }];
        $.each(data, function(i, item){
            let nodes = [];
            $.each(item, function(o, z){
                nodes.push({
                    text: z.name,
                    icon: "fa fa-file",
                    type: "cloudConfig",
                    id: z.id
                });
            });
            h.push({
                text: i,
                nodes: nodes
            });
        });

        $('#jsTreeSidebar').treeview({
            data: h,
            levels: 5,
            onNodeSelected: function(event, node) {
                if(node.type == "cloudConfig"){
                    loadCloudConfigView(node.id);
                } else if(node.type == "overview"){
                    loadCloudConfigOverview();
                }
            }
        });
    });
}

function loadCloudConfigOverview(){
    $(".boxSlide, #cloudConfigContents").hide();
    $("#cloudConfigBox, #cloudConfigOverview").show();
}

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
        cloudConfigId: currentCloudConfigId
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
