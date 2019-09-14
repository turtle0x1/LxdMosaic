<div id="clusterBox" class="boxSlide">
<div id="clusterOverview" class="row">
    <div class="col-md-9">
          <div id="clusterList">
          </div>
    </div>
</div>
<div class="row" id="clusterContents">
    <div class="col-md-9">
    </div>
</div>
</div>
<script>

var emptyClusterBoxTemplate = `<div class="card">
        <div class="card-header">
            <h4 class="clusterName"></h4>
        </div>
        <div class="card-body bg-dark text-white">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-center">Members</h5>
                    <table class="table table-dark table-bordered table-bordered memberTable">
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
                <div class="col-md-6">
                    <h5 class="text-center"> Stats </h5>
                    <table class="table table-dark table-bordered table-bordered statsTable">
                        <tbody>
                            <tr>
                                <th> Status </th>
                                <td class="status"> </td>
                            </tr>
                            <tr>
                                <th> Memory Usage </th>
                                <td class="memUsage"> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>`;

function loadClusterOverview(){
    $(".boxSlide, #clusterContents").hide();
    $("#clusterBox, #clusterOverview").show();
    $("#sidebar-ul").empty();
    loadClusterTree();
}

function loadClusterView(clusterId)
{
    let data = {
        id: cloudConfigId
    };

    currentCloudConfigId = cloudConfigId;

    ajaxRequest(globalUrls.cloudConfig.getDetails, data ,function(data){

        let config = $.parseJSON(data);
        $("#cloudConfigImage").tokenInput("clear");

        if(config.data.imageDetails.hasOwnProperty("description")){
            $("#cloudConfigImage").tokenInput("add", config.data.imageDetails);
        }

        editor.setValue(config.data.data, -1);
    });
}

function loadClusterTree()
{
    ajaxRequest(globalUrls.clusters.getAll, null, function(data){
        var data = $.parseJSON(data);
        let clusters = `
        <li class="nav-item active viewClusters">
            <a class="nav-link" href="#">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
        </li>`;
        $.each(data, function(i, cluster){
            clusters += `<li class="nav-item nav-dropdown open">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon fas fa-caret-down"></i> Cluster ${i}
                </a>
                <ul class="nav-dropdown-items">`;

            let template = $(emptyClusterBoxTemplate);

            template.find(".clusterName").text(`Cluster ${i}`);

            template.find(".card-header").addClass(cluster.stats.status == "Online" ? "bg-success" : "bg-warning");

            let membersHtml = "";

            $.each(cluster.members, function(o, z){

                membersHtml += `<tr>
                    <td>${z.server_name}</td>
                    <td>${z.status}</td>
                    <td><i class="fas fa-${z.database ? "check-circle" : "times"}"</td>
                </tr>`;

                clusters += `<li class="nav-item view-cluster"
                    data-id="${z.hostId}"
                    data-name="${z.server_name}"
                    data-cluster="${i}">
                  <a class="nav-link" href="#">
                    <i class="nav-icon fa fa-server"></i>
                    ${z.server_name}
                  </a>
                </li>`;
            });

            clusters += "</ul></li>";
            template.find(".memberTable > tbody").append(membersHtml);

            template.find(".statsTable .status").text(cluster.stats.status);

            template.find(".statsTable .memUsage").text(
                formatBytes(cluster.stats.usedMemory) +
                 "/" +
                 formatBytes(cluster.stats.totalMemory)
             );

            $("#clusterList").empty().append(template);
        });

        $("#sidebar-ul").empty().append(clusters);
    });
}

$("#sidebar-ul").on("click", ".view-cluster", function(){
    addBreadcrumbs(["Cluster " + $(this).data("cluster"), $(this).data("name")], ["", "active"]);
    loadClusterView($(this).data("id"));
});

</script>
