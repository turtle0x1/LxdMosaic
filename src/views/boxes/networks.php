<div id="networkBox" class="boxSlide">
<div id="networksOverview" class="row">
    <div class="col-md-9">
          <div class="card">
            <div class="card-header" role="tab" id="headingOne">
              <h5>
                <a data-toggle="collapse" data-parent="#accordion" href="#networkDescription" aria-expanded="true" aria-controls="networkDescription">
                  Networks
                </a>
              </h5>
            </div>
            <div id="networkDescription" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
              <div class="card-block">
                  Containers get attached to networks!
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
              </div>
            </div>
          </div>
    </div>
</div>
<div id="networkDetails" class="row">
<!-- <h4> Container: <`span id="containerName"></span> </h4> -->
<div class="col-md-3">
      <div class="card">
        <div class="card-header" role="tab" id="profilesActionHeading">
          <h5>
            <a data-toggle="collapse" data-parent="#accordion" href="#profileActions" aria-expanded="true" aria-controls="profileActions">
              Actions
            </a>
          </h5>
        </div>
        <div id="profileActions" class="collapse show" role="tabpanel" aria-labelledby="profilesActionHeading">
          <div class="card-block table-responsive">
              <div id="collapseOne" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
                <div class="card-block">
                    <button class="btn btn-block btn-warning" id="renameProfile">
                        Rename
                    </button>
                    <button class="btn btn-block btn-danger" id="deleteProfile">
                        Delete
                    </button>
                    <hr/>
                </div>
              </div>
          </div>
        </div>
      </div>
</div>
<div class="col-md-6">
      <div class="card">
        <div class="card-header" role="tab" id="profileDevicesHeading">
          <h5>
            <a id="devicesAriaToggle" data-toggle="collapse" data-parent="#profileDevicesHeading" href="#profileDevicesCard" aria-expanded="true" aria-controls="profileDevicesCard">
              Devices
            </a>
          </h5>
        </div>
        <div id="profileDevicesCard" class="collapse show" role="tabpanel" aria-labelledby="profileDevicesHeading">
          <div class="card-block table-responsive">
              <table class="table table-striped" id="profile-deviceData">
                    <thead class="thead-inverse">
                        <tr>
                            <th> Key </th>
                            <th> Value </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
              </table>
          </div>
        </div>
      </div>
      <br/>
      <div class="card">
        <div class="card-header" role="tab" id="configDeviceCardHeading">
          <h5>
            <a data-toggle="collapse" data-parent="#accordion" href="#configDeviceCard" aria-expanded="true" aria-controls="configDeviceCard">
              Config Data
            </a>
          </h5>
        </div>

        <div id="configDeviceCard" class="collapse show" role="tabpanel" aria-labelledby="configDeviceCardHeading">
          <div class="card-block table-responsive">
              <table class="table table-striped" id="profile-configData">
                    <thead class="thead-inverse">
                        <tr>
                            <th> Key </th>
                            <th> Value </th>
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
        <div class="card-header" role="tab" id="usedByCard">
          <h5>
            <a data-toggle="collapse" data-parent="#accordion" href="#usedByCollapse" aria-expanded="true" aria-controls="usedByCollapse">
              Entities Profile Used By
            </a>
          </h5>
        </div>

        <div id="usedByCollapse" class="collapse show" role="tabpanel" aria-labelledby="usedByCard">
          <div class="card-block">
              <table class="table table-responsive table-striped" id="profile-usedByData">
                    <thead class="thead-inverse">
                        <tr>
                            <th> Counter </th>
                            <th> Name </th>
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

function loadNetworkView()
{
    ajaxRequest(globalUrls.networks.getAll, null, function(data){
        var data = $.parseJSON(data);
        var treeData = [{
            text: "Overview",
            icon: "fa fa-home",
            type: "networkOverview",
            state: {
                selected: true
            }
        }];
        $.each(data, function(hostName, host){
            let networks = [];
            $.each(host.networks, function(index, networkName){
                networks.push({
                    text: networkName,
                    icon: "fa fa-user",
                    type: "network",
                    id: networkName,
                    host: hostName
                });
            });
            treeData.push({
                text: hostName,
                nodes: networks,
                icon: "fa fa-server"
            })
        });
        $(".boxSlide, #networkDetails").hide();
        $("#networksOverview, #networkBox").show();



        $('#jsTreeSidebar').treeview({
            data: treeData,         // data is not optional
            levels: 5,
            onNodeSelected: function(event, node) {
                if(node.type == "network"){
                    viewNetwork(node.id, node.host);
                } else if (node.type == "networkOverview"){
                    $(".boxSlide, #networkDetails").hide();
                    $("#networksOverview, #networkBox").show();
                }
            }
        });
        profileData = data;

    });
    changeActiveNav(".viewNetworks");
}

function viewNetwork(networkName, host){
    let x = {
        host: host,
        networkName: networkName
    }

    ajaxRequest(globalUrls.networks.get, x, function(data){
        console.log(data);
    });
}
</script>
