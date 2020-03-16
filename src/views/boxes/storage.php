<div id="storageBox" class="boxSlide">
    <div id="storageOverview" class="row">
        <div class="col-md-9">
              <div class="card bg-info">
                  <div class="card-body">
                      <h5>
                        <a class="text-white">
                          Storage
                        </a>
                      </h5>
                  </div>
              </div>
              <div class="card">
                <div class="card-header bg-info" role="tab" >
                  <h5>
                    <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                      Pool List
                    </a>
                  </h5>
                </div>
                <div id="cloudConfigDescription" class="collapse in show" role="tabpanel" >
                  <div class="card-block bg-dark">
                    <table class="table table-dark table-bordered" id="poolListTable">
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
                <div class="card-header bg-info" role="tab" >
                  <h5>
                    <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Actions
                    </a>
                  </h5>
                </div>
                <div id="collapseOne" class="collapse in show" role="tabpanel" >
                  <div class="card-block bg-dark">
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
                    <span class="text-white" id="storagePoolName"></span>
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
                    <div class="card-body table-responsive  bg-dark">
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

function makeStorageHostSidebarHtml(hosthtml, host, tableListHtml){
    let disabled = "";
    if(host.online == false){
        disabled = "disabled text-warning text-strikethrough";
    }

    hosthtml += `<li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle ${disabled}" href="#">
            <i class="fas fa-server"></i> ${host.alias}
        </a>
        <ul class="nav-dropdown-items">`;

     tableListHtml += `<tr><td class='bg-info text-white text-center' colspan='3'><h5>${host.alias}</h5></td></tr>`;

    $.each(host.pools, function(i, pool){
        hosthtml += `<li class="nav-item view-storagePool"
            data-host-id="${host.hostId}"
            data-pool-name="${pool.name}"
            >
          <a class="nav-link" href="#">
            <i class="nav-icon fa fa-hdd"></i>
            ${pool.name}
          </a>
        </li>`;
        tableListHtml += `<tr>
            <td>${pool.name}</td>
            <td>${formatBytes(pool.resources.space.used)}</td>
            <td>${formatBytes(pool.resources.space.total)}</td>
        </tr>`;
    });
    hosthtml += "</ul></li>";
    return {
        hosthtml: hosthtml,
        tableListHtml: tableListHtml
    };
}


function loadStorageView()
{
    $(".boxSlide, #storageDetails").hide();
    $("#storageOverview, #storageBox").show();
    $("#deploymentList").empty();
    setBreadcrumb("Storage", "viewStorage active");
    ajaxRequest(globalUrls.storage.getAll, {}, (data)=>{
        data = $.parseJSON(data);

        let hosts = `
        <li class="nav-item storage-overview">
            <a class="nav-link text-info" href="#">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>`;

        let tableList = "";

        $.each(data.clusters, (clusterIndex, cluster)=>{
            hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Cluster ${clusterIndex}</u></li>`;
            $.each(cluster.members, (_, host)=>{
                let html = makeStorageHostSidebarHtml(hosts, host, tableList)
                hosts = html.hosthtml;
                tableList = html.tableListHtml;
            })
        });

        hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Standalone Hosts</u></li>`;

        $.each(data.standalone.members, (_, host)=>{
            let html = makeStorageHostSidebarHtml(hosts, host, tableList)
            hosts = html.hosthtml;
            tableList = html.tableListHtml;
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
