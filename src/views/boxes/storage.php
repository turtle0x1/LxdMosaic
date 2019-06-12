<div id="storageBox" class="boxSlide">
    <div id="storageOverview" class="row">
        <div class="col-md-9">
              <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                  <h5>
                    <a data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                      Storage
                    </a>
                  </h5>
                </div>
                <div id="cloudConfigDescription" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
                  <div class="card-block">
                      Deployments are used to deploy multiple cloud configs to multiple
                      containers.
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                  <h5>
                    <a data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                      Pool List
                    </a>
                  </h5>
                </div>
                <div id="cloudConfigDescription" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
                  <div class="card-block">
                    <table class="table table-bordered" id="poolListTable">
                        <thead>
                            <tr>
                                <th>Pool</th>
                                <th>Used</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
                      <button class="btn btn-block btn-primary" id="createPool">
                          Create
                      </button>
                  </div>
                </div>
              </div>
        </div>
    </div>
    <div id="storageDetails">
        <div class="row mb-4" style="border-bottom: 1px solid black; padding-bottom: 10px">
            <div class="col-md-12">
                <h4>
                    <span id="storagePoolName"></span>
                    <button class="btn btn-danger float-right" id="deletePool">Delete Pool</button>
                </h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                  <div class="card">
                    <div class="card-header text-center bg-info" role="tab" id="deploymentCloudConfigHeading">
                      <h5>
                        <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#deploymentCloudConfig" aria-expanded="true" aria-controls="deploymentCloudConfig">
                        Config
                        </a>
                      </h5>
                    </div>
                    <div id="deploymentCloudConfig" class="collapse show" role="tabpanel" aria-labelledby="deploymentCloudConfigHeading">
                      <div class="card-block bg-dark table-responsive">
                          <table class="table table-bordered table-dark" id="storagePoolConfigDetails">
                          </table>
                      </div>
                    </div>
                  </div>
            </div>
            <div class="col-md-3">
                  <div class="card">
                    <div class="card-header text-center bg-info" role="tab" id="deploymentCloudConfigHeading">
                      <h5>
                        <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#deploymentCloudConfig" aria-expanded="true" aria-controls="deploymentCloudConfig">
                        Usage
                        </a>
                      </h5>
                    </div>
                    <div id="deploymentCloudConfig" class="collapse show" role="tabpanel" aria-labelledby="deploymentCloudConfigHeading">
                      <div class="card-block bg-dark table-responsive">
                          <h5 id="storagePoolUsage"></h5>
                          <h5 id="storagePoolTotal"></h5>
                      </div>
                    </div>
                  </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-center">
                        <h5> <a> Used By </a> </h5>
                    </div>
                    <div class="card-body bg-dark">
                        <table class="table table-bordered table-dark" id="storagePoolUsedBy">
                            <thead>
                                <tr>
                                    <th> Entity </th>
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

<script>

var currentPool = {};

function loadStorageView()
{
    $(".boxSlide, #storageDetails").hide();
    $("#storageOverview, #storageBox").show();
    $("#deploymentList").empty();
    setBreadcrumb("Storage", "viewStorage active");
    ajaxRequest(globalUrls.storage.getAll, {}, (data)=>{
        data = $.parseJSON(data);

        let hosts = `
        <li class="nav-item active storage-overview">
            <a class="nav-link" href="#">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>`;

        let tableList = "";

        $.each(data, function(hostName, data){
            if(data.online == false){
                hostName += " (Offline)";
            }
            hosts += `<li class="nav-item nav-dropdown open">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fas fa-server"></i> ${hostName}
                </a>
                <ul class="nav-dropdown-items">`;

            tableList += `<tr><td class='bg-info text-white text-center' colspan='3'><h5>${hostName}</h5></td></tr>`;

            $.each(data.pools, function(i, pool){
                hosts += `<li class="nav-item view-storagePool"
                    data-host-id="${data.hostId}"
                    data-pool-name="${pool.name}"
                    data-alias="${pool.name}">
                  <a class="nav-link" href="#">
                    <i class="nav-icon fa fa-hdd"></i>
                    ${pool.name}
                  </a>
                </li>`;
                tableList += `<tr>
                    <td>${pool.name}</td>
                    <td>${formatBytes(pool.resources.space.used)}</td>
                    <td>${formatBytes(pool.resources.space.total)}</td>
                    </tr>`;
            });
                hosts += "</ul></li>";
        });
        $("#sidebar-ul").empty().append(hosts);
        $("#poolListTable > tbody").empty().append(tableList);
    });
}

$("#sidebar-ul").on("click", ".view-storagePool", function(){
    viewStoragePool($(this).data("hostId"), $(this).data("poolName"))
});


$("#storageOverview").on("click", "#createPool", function(){
    $("#modal-storage-createPool").modal("toggle");
});

function viewStoragePool(hostId, poolName)
{
    currentPool = {hostId: hostId, poolName: poolName};
    $("#storageOverview").hide();
    $("#storageDetails").show();
    ajaxRequest(globalUrls.storage.getPool, currentPool, function(data){
        data = $.parseJSON(data);
        let configHtml = "",
            usedByHtml = "";

        $("#storagePoolName").text(`Storage Pool: ${data.name} (${data.driver})`);

        $.each(data.config, function(key, value){
            configHtml += `<tr><th>${key}</th><td>${value}</td></tr>`
        });
        if(data.used_by.length == 0){
            usedByHtml += `<tr><td>Not Used</td></tr>`
        }else{
            $.each(data.used_by, function(key, value){
                usedByHtml += `<tr><td>${value}</td></tr>`
            });
        }

        $("#storagePoolUsage").text(`Total Used: ${formatBytes(data.resources.space.used)}`)
        $("#storagePoolTotal").text(`Total Free: ${formatBytes(data.resources.space.total)}`)

        $("#storagePoolConfigDetails").empty().append(configHtml);
        $("#storagePoolUsedBy > tbody").empty().append(usedByHtml);
    });
}

$("#storageDetails").on("click", "#deletePool", function(){
    $.confirm({
        title: 'Delete Storage Pool?',
        content: 'This can\'t be undone?!',
        buttons: {
            cancel: function () {},
            yes: {
                btnClass: 'btn-danger',
                action: function () {
                    this.buttons.yes.setText('<i class="fa fa-cog fa-spin"></i>Deleting..'); // let the user know
                    this.buttons.yes.disable();
                    this.buttons.cancel.disable();
                    var modal = this;
                    ajaxRequest(globalUrls.storage.deletePool, currentPool, (data)=>{
                        data = makeToastr(data);
                        modal.close();
                        if(data.state == "success"){
                            loadStorageView();
                        }
                    });
                    return false;
                }
            }
        }
    });
});

</script>

<?php
    require __DIR__ . "/../modals/storage/createPool.php";
?>
