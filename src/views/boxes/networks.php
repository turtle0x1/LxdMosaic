<div id="networkBox" class="boxSlide">
    <div id="networkOverview">
        <div class="row border-bottom mb-2">
            <div class="col-md-12 text-center">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                    <h4> Networks </h4>
                    <div class="btn-toolbar float-end">
                      <div class="btn-group me-2">
                          <button data-toggle="tooltip" data-placement="bottom" title="Create network" class="btn btn-primary" id="createNetwork">
                              <i class="fas fa-plus"></i>
                          </button>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="networksOverview">
            </div>
        </div>
    </div>
    <div id="networkInfo">
        <div class="row mb-4" style="border-bottom: 1px solid black; padding-bottom: 10px">
            <div class="col-md-12">
                <h4>
                    <span  id="networkName"></span>
                    <button class="btn btn-danger float-end" id="deleteNetwork">
                        <i class="fas fa-trash"></i>
                    </button>
                </h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                  <div class="card bg-dark text-white">
                    <div class="card-header" role="tab" id="deploymentCloudConfigHeading">
                      <h5>
                        <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#deploymentCloudConfig" aria-expanded="true" aria-controls="deploymentCloudConfig">
                        Config
                        </a>
                      </h5>
                    </div>
                    <div id="deploymentCloudConfig" class="collapse show" role="tabpanel" aria-labelledby="deploymentCloudConfigHeading">
                      <div class="card-block bg-dark table-responsive">
                          <table class="table table-bordered table-dark" id="networkConfigDetails">
                          </table>
                      </div>
                    </div>
                  </div>
            </div>
            <div class="col-md-8">
                <div class="card bg-dark text-white">
                    <div class="card-header bg-dark">
                        <h5> Used By <i class="fas fa-layer-group float-end"></i> </h5>
                    </div>
                    <div class="card-body bg-dark">
                        <table class="table table-bordered table-dark" id="networkUsedBy">
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

var currentNetwork = {};

function makeNetworkHostSidebarHtml(hosthtml, host){
    let disabled = "";

    if(host.hostOnline == false){
        disabled = "disabled text-warning text-strikethrough";
    }

    let currentId = "a";

    hosthtml += `<li class="mb-2">
        <a class="d-inline href="#">
            <i class="fas fa-server"></i> ${host.alias}
        </a>
        <button class="btn  btn-outline-secondary btn-sm btn-toggle align-items-center rounded d-inline ${disabled} float-end me-2" data-bs-toggle="collapse" data-bs-target="#${currentId}" aria-expanded="true">
            <i class="fas fa-caret-down"></i>
        </button>
        <div class=" mt-2 bg-dark text-white" id="${currentId}">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small hostInstancesUl" style="display: inline;">`

    $.each(host.networks, function(_, network){
        hosthtml += `<li class="nav-item view-network"
            data-host-id="${host.hostId}"
            data-network="${network}"
            data-alias="${host.alias}">
            <a class="nav-link" href="#">
            <i class="fas fa-ethernet"></i>
            ${network}
            </a>
        </li>`;

    });
    hosthtml += "</ul></li>";
    return hosthtml;
}

function loadNetworksView()
{
    $(".boxSlide, #networkInfo").hide();
    $("#networkOverview, #networkBox").show();
    $("#deploymentList").empty();
    setBreadcrumb("Networks", "viewNetwork active");
    ajaxRequest(globalUrls.networks.getAll, {}, (data)=>{
        data = $.parseJSON(data);


        let hosts = `
        <li class="nav-item network-overview">
            <a class="nav-link text-info" href="#">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>`;


        $.each(data.clusters, (clusterIndex, cluster)=>{
            hosts += `<li class="c-sidebar-nav-title text-success ps-1 pt-2"><u>Cluster ${clusterIndex}</u></li>`;
            $.each(cluster.members, (_, host)=>{
                hosts = makeNetworkHostSidebarHtml(hosts, host)
            })
        });

        hosts += `<li class="c-sidebar-nav-title text-success ps-1 pt-2"><u>Standalone Hosts</u></li>`;

        $.each(data.standalone.members, (_, host)=>{
            hosts = makeNetworkHostSidebarHtml(hosts, host)
        });

        $("#sidebar-ul").empty().append(hosts);
    });
    // Dont normally like 2 requests to load dashboard but this could be slow
    ajaxRequest(globalUrls.networks.getDashboard, {}, (data)=>{
        data = $.parseJSON(data);
        let html = "";
        $("#networksOverview").empty().append(`<h4 class="text-center"><i class="fas fa-cog fa-spin"></i></h4>`)
        $.each(data.standalone.members, (_, host)=>{
            if(!host.hostOnline || host.instances.length == 0){
                return true;
            }
            let hostTotals = host.totals;
            let hostHtml = {};
            $.each(host.instances, (instance, interfaces)=>{
                $.each(interfaces, (interfaceName, iTotals)=>{
                    $.each(iTotals, (key, used)=>{
                        if(!hostHtml.hasOwnProperty(key)){
                            hostHtml[key] = "";
                        }

                        let percent = (used / hostTotals[key]) * 100
                        let formatedUsed = key.includes("packets") ? parseFloat(used).toLocaleString('en') : formatBytes(used)
                        let formatedTotal = key.includes("packets") ? parseFloat(hostTotals[key]).toLocaleString('en') : formatBytes(hostTotals[key])
                        let formatedPercent = parseFloat(percent).toFixed(2);
                        hostHtml[key] += `${instance} - ${interfaceName} - ${key} - ${formatedPercent}% <div class="progress mb-2" data-toggle="tooltip" data-placement="right" title="${formatedTotal}">
                            <div class="progress-bar" role="progressbar" data-toggle="tooltip" title="${formatedUsed} - ${formatedPercent}%" style="width: ${percent}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>`
                    });

                });
            });
            html += `<div class='row mb-2'>
                <div class="col-md-12">
                    <h4><i class="fas fa-server me-2"></i>${host.alias} <i class="fas fa-project-diagram ms-2 me-2"></i>${host.project}</h4>
                </div>
            `
            $.each(Object.keys(hostHtml), (_, key)=>{
                html += `<div class="col-md-3 border-end">${hostHtml[key]}</div>`;
            });
            html += `</div>`;
        });
        $("#networksOverview").empty().append(html).find('[data-toggle="tooltip"]').tooltip();
    });
}

$("#sidebar-ul").on("click", ".view-network", function(){
    viewNetwork($(this).data("hostId"), $(this).data("network"), $(this).data("alias"))
});


$("#networkOverview").on("click", "#createNetwork", function(){
    $("#modal-networks-create").modal("toggle");
});

function viewNetwork(hostId, network, alias)
{
    currentNetwork = {hostId: hostId, network: network};
    $("#networkOverview").hide();
    $("#networkInfo").show();
    addBreadcrumbs([alias, network], ["", "active"]);
    ajaxRequest(globalUrls.networks.get, currentNetwork, function(data){
        data = $.parseJSON(data);
        let configHtml = "",
            usedByHtml = "";

        $("#networkName").text(`Network: ${data.name} (${data.type})`);

        if(data.config.length == 0){
            configHtml += `<tr><td>No Config Settings</td></tr>`
        }else{
            $.each(data.config, function(key, value){
                configHtml += `<tr><th>${key}</th><td>${value}</td></tr>`
            });
        }

        if(data.used_by == null || data.used_by.length == 0){
            usedByHtml += `<tr><td>Not Used</td></tr>`
        }else{
            $.each(data.used_by, function(key, value){
                usedByHtml += `<tr><td>${value}</td></tr>`
            });
        }

        $("#networkConfigDetails").empty().append(configHtml);
        $("#networkUsedBy > tbody").empty().append(usedByHtml);
    });
}

$("#networkInfo").on("click", "#deleteNetwork", function(){
    $.confirm({
        title: 'Delete Network?',
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
                    ajaxRequest(globalUrls.networks.deleteNetwork, currentNetwork, (data)=>{
                        data = makeToastr(data);
                        modal.close();
                        if(data.state == "success"){
                            currentNetwork = {};
                            loadNetworksView();
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
    require __DIR__ . "/../modals/networks/createNetwork.php";
?>
