<div id="networkBox" class="boxSlide">
    <div id="networkOverview" class="row">
        <div class="col-md-9">
              <div class="card bg-info">
                <div class="card-body">
                  <h5>
                    <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#cloudConfigDescription" aria-expanded="true" aria-controls="cloudConfigDescription">
                      Networks
                    </a>
                  </h5>
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
                      <button class="btn btn-block btn-primary" id="createNetwork">
                          Create
                      </button>
                  </div>
                </div>
              </div>
        </div>
    </div>
    <div id="networkInfo">
        <div class="row mb-4" style="border-bottom: 1px solid black; padding-bottom: 10px">
            <div class="col-md-12">
                <h4>
                    <span class="text-white" id="networkName"></span>
                    <button class="btn btn-danger float-right" id="deleteNetwork">Delete Network</button>
                </h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
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
                          <table class="table table-bordered table-dark" id="networkConfigDetails">
                          </table>
                      </div>
                    </div>
                  </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info text-center">
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

function loadNetworksView()
{
    $(".boxSlide, #networkInfo").hide();
    $("#networkOverview, #networkBox").show();
    $("#deploymentList").empty();
    setBreadcrumb("Networks", "viewNetwork active");
    ajaxRequest(globalUrls.networks.getAll, {}, (data)=>{
        data = $.parseJSON(data);

        let hosts = `
        <li class="nav-item active network-overview">
            <a class="nav-link" href="#">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>`;

        $.each(data, function(hostName, data){
            let disabled = "";
            if(data.online == false){
                disabled = "disabled text-warning";
                hostName += " (Offline)";
            }
            hosts += `<li class="nav-item nav-dropdown open">
                <a class="nav-link nav-dropdown-toggle ${disabled}" href="#">
                    <i class="fas fa-server"></i> ${hostName}
                </a>
                <ul class="nav-dropdown-items">`;

            $.each(data.networks, function(i, network){
                hosts += `<li class="nav-item view-network"
                    data-host-id="${data.hostId}"
                    data-network="${network}"
                    data-alias="${hostName}">
                  <a class="nav-link" href="#">
                    <i class="fas fa-ethernet"></i>
                    ${network}
                  </a>
                </li>`;
            });
                hosts += "</ul></li>";
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

        if(data.used_by.length == 0){
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
