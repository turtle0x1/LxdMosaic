<div id="clusterBox" class="boxSlide">
    <div id="clusterOverview">
        <div class="row">
            <div class="col-md-12" id="clusterList">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                    <h4 id="clusterName"></h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 alert alert-warning text-center" id="twoMemberWarning">
                <h4 class="text-dark text-underline">Two Memeber Cluster Add A Third</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h4>Nodes</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-dark table-bordered table-bordered" id="memberTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Database Node</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h4>Stats</h4>
                    </div>
                    <div class="card-body text-center">
                        <div id="clusterMemoryGraph"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

var currentCluster = {
    clusterId: null
};

function loadClusterView(req){
    let clusterId = req.data.clusterId;
    currentCluster.clusterId = clusterId;
    createDashboardSidebar()
    if(!userDetails.isAdmin){
        router.navigate("/404")
        return false;
    }
    let x = $(this).data();
    $("#sidebar-ul").find(".text-info").removeClass("text-info");
    $("#sidebar-ul").find(".active").removeClass("active");
    $(this).addClass("text-info");

    $(".boxSlide, #clusterContents").hide();
    $("#clusterBox, #clusterOverview").show();

    ajaxRequest(globalUrls.clusters.getCluster, {cluster: clusterId}, (data)=>{
        cluster = makeToastr(data);

        $("#clusterName").text(`Cluster ${clusterId}: ${cluster.stats.status}`);

        let membersHtml = "";

        if(cluster.members.length < 3){
            $("#twoMemberWarning").show();
        }else{
            $("#twoMemberWarning").hide();
        }

        $.each(cluster.members, function(o, z){
            membersHtml += `<tr>
                <td><a href="/host/${z.hostId}/overview">${z.clusterInfo.server_name}</a></td>
                <td>${z.clusterInfo.status}</td>
                <td><i class="fas fa-${z.clusterInfo.database ? "check-circle" : "times"}"</td>
            </tr>`;
        });

        $("#memberTable > tbody").empty().append(membersHtml);
        $("#clusterStats .memUsage").text(
            formatBytes(cluster.stats.usedMemory) +
             "/" +
             formatBytes(cluster.stats.totalMemory)
        );

        let memoryWidth = ((cluster.stats.usedMemory / cluster.stats.totalMemory) * 100)
        $("#clusterMemoryGraph").empty().append(`
            <h4 class='float-left'>Memory</h4>
            <br/>
            <br/>
            <div data-bs-toggle="tooltip" data-bs-placement="bottom" title="${formatBytes(cluster.stats.totalMemory)}" class="progress mt-2">
                <div data-bs-toggle="tooltip" data-bs-placement="bottom" title="${formatBytes(cluster.stats.usedMemory)}" class="progress-bar bg-success" style="width: ${memoryWidth}%" role="progressbar" aria-valuenow="${cluster.stats.usedMemory}" aria-valuemin="0" aria-valuemax="${(cluster.stats.totalMemory - cluster.stats.usedMemory)}"></div>
            </div>
        `);
        $("#clusterMemoryGraph").find('[data-bs-toggle="tooltip"]').tooltip({html: true})
    });
}
</script>
