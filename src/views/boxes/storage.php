<div id="storageBox" class="boxSlide">
    <div id="storageOverview">
        <div class="row border-bottom mb-2">
            <div class="col-md-12 text-center">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                    <h4> Storage </h4>
                    <div class="btn-toolbar float-right">
                      <div class="btn-group mr-2">
                          <button data-toggle="tooltip" data-placement="bottom" title="Create storage pool" class="btn btn-primary" id="createPool">
                              <i class="fas fa-plus"></i>
                          </button>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                  <div class="card bg-dark">
                    <div class="card-header " role="tab" >
                      <h5>
                        <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                            All Pools
                        </a>
                      </h5>
                    </div>
                    <div class="collapse in show" role="tabpanel" >
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
                <div class="card bg-dark text-white">
                    <div class="card-header">
                        Total Storage Usage
                    </div>
                    <div class="card-body">
                        <div id="currentStorageUsageTotal"></div>
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
                    <button class="btn btn-danger float-right" id="deletePool">
                        <i class="fas fa-trash"></i>
                    </button>
                </h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-dark">
                  <div class="card-header" role="tab" id="deploymentCloudConfigHeading">
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
                  <div class="card bg-dark">
                    <div class="card-header" role="tab" id="deploymentCloudConfigHeading">
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
                <div class="card bg-dark">
                  <div class="card-header" role="tab">
                    <h5>Volumes <i class="fas fa-database float-right"></i></h5>
                  </div>
                  <div class="card-body">
                      <table class="table table-bordered table-dark" id="storageVolumeTable">
                          <thead>
                              <tr>
                                  <th>Volume</th>
                                  <th>Project</th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                  </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-dark">
                    <div class="card-header">
                        <h5>Used By <i class="fas fa-layer-group float-right"></i> </h5>
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
    <div id="volumeDetails">
        <div class="row mb-4" style="border-bottom: 1px solid black; padding-bottom: 10px">
            <div class="col-md-12">
                <h4>
                    <i class="fas fa-database text-white mr-2"></i>
                    <span class="text-white" id="storageVolumeName"></span>
                </h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-dark">
                  <div class="card-header">
                    <h5>
                        <i class="fas fa-cog mr-2"></i>Config
                    </h5>
                  </div>
                  <div class="card-body">
                      <table class="table table-borderd table-dark" id="volumeConfigTable">
                          <thead>
                              <th>Key</th>
                              <th>Value</th>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                  </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card bg-dark">
                  <div class="card-header">
                    <h5>
                        <i class="fas fa-layer-group float-right"></i>Used By
                    </h5>
                  </div>
                  <div class="card-body">
                      <table class="table table-borderd table-dark" id="volumeUsedByTable">
                          <thead>
                              <th>Entity</th>
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
    let offlineText = "";
    if(host.hostOnline == false){
        disabled = "disabled text-warning text-strikethrough";
    }

    hosthtml += `<li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle ${disabled}" href="#">
            <i class="fas fa-server"></i> ${host.alias}
        </a>
        <ul class="nav-dropdown-items">`;


    if(host.hostOnline == true) {
        tableListHtml += `<tr><td class='text-info text-center' colspan='3'><h5>${host.alias}${offlineText}</h5></td></tr>`;
    }



     $.each(host.pools, function(i, pool){
         hosthtml += `<li class="nav-item view-storagePool"
             data-host-id="${host.hostId}"
             data-host-alias="${host.alias}"
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
    $(".boxSlide, #storageDetails, #volumeDetails").hide();
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

        let memoryWidth = ((data.stats.storage.used / data.stats.storage.total) * 100)
        let formatedStorageTitle = formatBytes(data.stats.storage.used);

        $("#currentStorageUsageTotal").empty().append(`<canvas id="containerStatsChart" style="width: 100%;"></canvas>`);

        new Chart($("#containerStatsChart"), {
            type: 'pie',
              data: {
                labels: ['Used', 'Total'],
                datasets: [{
                  label: '# of containers',
                  data: [data.stats.storage.used, data.stats.storage.total],
                  backgroundColor: [
                    'rgba(46, 204, 113, 1)',
                    'rgba(189, 195, 199, 1)'
                  ],
                  borderColor: [
                      'rgba(46, 204, 113, 1)',
                      'rgba(189, 195, 199, 1)'
                  ],
                  borderWidth: 1
                }]
              },
              options: {
                cutoutPercentage: 40,
                responsive: false,
                // scales: scalesBytesCallbacks,
                tooltips: toolTipsBytesCallbacks
              }
        });

        // $("#currentStorageUsageTotal").empty().append(`<div class="mb-2">
        //     <label>Memory</label>
        //     <div class="progress">
        //         <div data-toggle="tooltip" data-placement="bottom" title="${formatBytes(data.stats.storage.used)}" class="progress-bar bg-success" style="width: ${memoryWidth}%" role="progressbar" aria-valuenow="${data.stats.storage.used}" aria-valuemin="0" aria-valuemax="${(data.stats.storage.total - data.stats.storage.used)}"></div>
        //     </div>
        // </div>`);
        //
        // $("#currentStorageUsageTotal").find('[data-toggle="tooltip"]').tooltip({html: true})

        $.each(data.hostDetails.clusters, (clusterIndex, cluster)=>{
            hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Cluster ${clusterIndex}</u></li>`;
            $.each(cluster.members, (_, host)=>{
                let html = makeStorageHostSidebarHtml(hosts, host, tableList)
                hosts = html.hosthtml;
                tableList = html.tableListHtml;
            })
        });

        hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Standalone Hosts</u></li>`;

        $.each(data.hostDetails.standalone.members, (_, host)=>{
            let html = makeStorageHostSidebarHtml(hosts, host, tableList)
            hosts = html.hosthtml;
            tableList = html.tableListHtml;
        });

        $("#sidebar-ul").empty().append(hosts);
        $("#poolListTable > tbody").empty().append(tableList);
    });
}

$("#sidebar-ul").on("click", ".view-storagePool", function(){
    viewStoragePool($(this).data("hostId"), $(this).data("hostAlias"), $(this).data("poolName"))
});


$("#storageOverview").on("click", "#createPool", function(){
    $("#modal-storage-createPool").modal("toggle");
});

function viewStoragePool(hostId, hostAlias, poolName)
{
    currentPool = {hostId: hostId, poolName: poolName, hostAlias: hostAlias};
    $("#storageOverview, #volumeDetails").hide();
    $("#storageDetails").show();

    addBreadcrumbs(["Storage", hostAlias, poolName], ["viewStorage", "", "active"], false);

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

        let volumesHtml = "";

        if(data.volumes.length == 0){
            volumesHtml += `<tr><td class="text-center" colspan="2"><i class="fas fa-info-circle text-info mr-2"></i>No Volumes</td></tr>`
        }else{
            $.each(data.volumes, function(key, value){
                volumesHtml += `<tr>
                    <td><a class='viewVolume' href="#" data-project="${value.project}" data-path="${value.path}" data-name="${value.name}">${value.name}</a></td>
                    <td>${value.project}</td>
                </tr>`
            });
        }

        $("#storageVolumeTable > tbody").empty().append(volumesHtml);
        $("#storagePoolConfigDetails").empty().append(configHtml);
        $("#storagePoolUsedBy > tbody").empty().append(usedByHtml);
    });
}

$("#storageDetails").on("click", ".viewVolume", function(){
    let d = $(this).data();
    $("#storageOverview, #storageDetails").hide();
    $("#volumeDetails").show();

    viewStorageVolume(currentPool.hostId, currentPool.hostAlias, currentPool.poolName, d.name, d.path, d.project);
});

function viewStorageVolume(hostId, hostAlias, poolName, volumeName, path, project) {
    let x = {hostId: hostId, pool: poolName, path: path, project: project};
    addBreadcrumbs(["Storage", hostAlias, poolName, "volumes", volumeName ], ["viewStorage", "", "", "", "active"], false);
    $("#storageVolumeName").text(volumeName)
    ajaxRequest(globalUrls.storage.volumes.get, x, (data)=>{
        data = makeToastr(data);
        let volumeConfigTrs = "";
        let configKeys = Object.keys(data.config);
        if(configKeys.length == 0){
            volumeConfigTrs = `<tr>
                <td colspan="2" class="text-center"><i class="fas fa-info-circle text-info mr-2"></i>No Config</td>
            </tr>`;

        }else{
            $.each(configKeys, (_, key)=>{
                volumeConfigTrs += `<tr>
                    <td>${key}</td>
                    <td>${data.config[key]}</td>
                </tr>`
            });
        }

        let volumeUsedByTrs = "";

        if(data.used_by.length == 0){
            volumeUsedByTrs += `<tr><td class="text-center"><i class="fas fa-info-circle text-info mr-2"></i>Not Used</td></tr>`
        }else{
            $.each(data.used_by, function(key, value){
                volumeUsedByTrs += `<tr><td>${value}</td></tr>`
            });
        }


        $("#volumeUsedByTable > tbody").empty().append(volumeUsedByTrs)
        $("#volumeConfigTable > tbody").empty().append(volumeConfigTrs)
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
