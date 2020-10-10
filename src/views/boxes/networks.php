<div id="networkBox" class="boxSlide">
    <div id="networkOverview">
        <div class="row border-bottom mb-2">
            <div class="col-md-12 text-center">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                    <h4> Networks </h4>
                    <div class="btn-toolbar float-right">
                      <div class="btn-group mr-2">
                          <button data-toggle="tooltip" data-placement="bottom" title="Create network" class="btn btn-primary" id="createNetwork">
                              <i class="fas fa-plus"></i>
                          </button>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="networkInfo">
        <div class="row mb-4" style="border-bottom: 1px solid black; padding-bottom: 10px">
            <div class="col-md-12">
                <h4>
                    <span  id="networkName"></span>
                    <button class="btn btn-danger float-right" id="deleteNetwork">
                        <i class="fas fa-trash"></i>
                    </button>
                </h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
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
                          <table class="table table-bordered table-dark" id="networkConfigDetails">
                          </table>
                      </div>
                    </div>
                  </div>
            </div>
            <div class="col-md-8">
                <div class="card bg-dark">
                    <div class="card-header bg-dark">
                        <h5> <a> Used By </a> </h5>
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

    hosthtml += `<li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle ${disabled}" href="#">
            <i class="fas fa-server"></i> ${host.alias}
        </a>
        <ul class="nav-dropdown-items">`;



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
            hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Cluster ${clusterIndex}</u></li>`;
            $.each(cluster.members, (_, host)=>{
                hosts = makeNetworkHostSidebarHtml(hosts, host)
            })
        });

        hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Standalone Hosts</u></li>`;

        $.each(data.standalone.members, (_, host)=>{
            hosts = makeNetworkHostSidebarHtml(hosts, host)
        });

        $("#sidebar-ul").empty().append(hosts);
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
