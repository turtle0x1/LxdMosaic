<div id="serverBox" class="boxSlide">
    <div id="serverOverview" class="row">
        <div class="col-md-9">
          <div class="row">
              <div class="col-md-6">
                  <div class="card bg-dark">
                      <div class="card-header">
                          Container Stats
                      </div>
                      <div class="card-body">
                          <canvas id="containerStatsChart" style="width: 100%;"></canvas>
                          <div class="alert alert-info" id="noContainersWarning">
                              There are no containers on the host
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="card bg-dark">
                      <div class="card-header">
                          Memory Stats
                      </div>
                      <div class="card-body">
                          <canvas id="memoryStatsChart" style="width: 100%;">
                      </div>
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12">
              <div class="card">
                  <div class="card-header bg-dark">
                      <h4> Containers </h4>
                  </div>
                  <div class="card-body bg-dark">
                      <table id="containerTable" class="table table-dark table-bordered">
                          <thead>
                              <tr>
                                  <td> Name </td>
                                  <td> Disk Usage </td>
                                  <td> Memory Ussage </td>
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
        <div class="col-md-3">
              <div class="card">
                <div class="card-header bg-info" role="tab" id="headingOne">
                  <h5>
                    <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Actions
                    </a>
                  </h5>
                </div>
                <div id="collapseOne" class="collapse in show" role="tabpanel" aria-labelledby="headingOne">
                  <div class="card-block bg-dark">
                      <button class="btn btn-block btn-danger" id="deleteHost">
                          Delete Host
                      </button>
                  </div>
                </div>
              </div>
        </div>
    </div>
    <div id="serverDetails">
    </div>
</div>

<script>

var currentServer = {
    hostId: null
};

function loadServerView(hostId)
{
    $(".boxSlide, #serverDetails").hide();
    $("#serverOverview, #serverBox").show();

    $("#sidebar-ul").find(".active").removeClass("active");
    $("#sidebar-ul").find(`[data-hostId='${hostId}'] > a:eq(0)`).addClass("text-info");

    currentServer.hostId = hostId;

    ajaxRequest(globalUrls.hosts.getHostOverview, {hostId: hostId}, (data)=>{
        data = $.parseJSON(data);

        let ident = data.header.alias == null ? data.header.urlAndPort : data.header.alias;

        addBreadcrumbs([ident], ["active"]);

        let containerHtml = "";

        if(Object.keys(data.containers)){
            $.each(data.containers, function(state, containers){
                containerHtml += `<tr>
                    <td class="text-center bg-info" colspan="999">
                        <i class="${statusCodeIconMap[containers[Object.keys(containers)[0]].state.status_code]}"></i>
                        ${state}
                    </td>
                </tr>`;
                $.each(containers, function(name, details){
                    let storageUsage = details.state.disk == null ? "N/A" : formatBytes(details.state.disk.root.usage);

                    containerHtml += `<tr>
                        <td>${name}</td>
                        <td>${storageUsage}</td>
                        <td>${formatBytes(details.state.memory.usage)}</td>
                    </tr>`
                });
            });

            new Chart($("#containerStatsChart"), {
                type: 'pie',
                  data: {
                    labels: ['Online', 'Offline'],
                    datasets: [{
                      label: '# of containers',
                      data: [data.containerStats.online, data.containerStats.offline],
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
                  }
            });
            $("#noContainersWarning").hide();
            $("#containerStatsChart").show();
        }else{
            $("#containerStatsChart").hide();
            $("#noContainersWarning").show();
            containerHtml = `<tr><td class="alert alert-info text-center" colspan="999">No Containers</td></tr>`
        }

        $("#containerTable > tbody").empty().append(containerHtml);

        new Chart($("#memoryStatsChart"), {
            type: 'pie',
              data: {
                labels: ['Used', 'Free'],
                datasets: [{
                  label: '# of containers',
                  data: [data.resources.memory.used, (data.resources.memory.total - data.resources.memory.used)],
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
                tooltips: toolTipsBytesCallbacks
              }
        });
    });
}

$(document).on("click", "#deleteHost", function(){
    let hostId = $(this).parents(".brand-card").attr("id");
    $.confirm({
        title: 'Delete Host',
        content: 'Are you sure you want to remove this host!',
        buttons: {
            cancel: function () {},
            yes: {
                btnClass: 'btn-danger',
                action: function () {
                    this.buttons.yes.setText('<i class="fa fa-cog fa-spin"></i>Deleting..'); // let the user know
                    this.buttons.yes.disable();
                    this.buttons.cancel.disable();
                    var modal = this;
                    ajaxRequest(globalUrls.hosts.delete, {hostId: currentServer.hostId}, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            return false;
                        }
                        location.reload();
                    });
                    return false;
                }
            }
        }
    });
});
</script>
