<div id="backupsBox" class="boxSlide">
<div id="backupsOverview">
    <div class="row">
        <div class="col-md-8">
            <div class="card bg-dark">
                <div class="card-header">
                    <h4>Host Containers Local Backups</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-dark" id="containerBackupTable">
                        <thead>
                            <tr>
                                <th>Container</th>
                                <th>Created</th>
                                <th>Last Backed Up</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4" id="graphCards">
              <div class="card bg-dark">
                  <div class="card-header">
                      <h4> Local Backup Disk Usage </h4>
                  </div>
                  <div class="card-body">
                      <canvas style="width: 100%; max-height: 400px; width: 400px;" id="backupsSizeChart"></canvas>
                  </div>
              </div>
              <div class="card bg-dark">
                  <div class="card-header">
                      <h4> Total Backup Files </h4>
                  </div>
                  <div class="card-body">
                      <canvas style="width: 100%; max-height: 400px; width: 400px;" id="backupFilesChart"></canvas>
                  </div>
              </div>
        </div>
        <div class="col-md-4" id="containerBackupDetails">
              <div class="card bg-dark">
                  <div class="card-header">
                      <h4>
                          <span id="backupContainerName"></span>
                          Backups
                          <button class="btn btn-primary btn-sm float-right" id="backToBackupGraphs">
                              Back to graphs
                          </button>
                      </h4>
                  </div>
                  <div class="card-body">
                      <table class="table table-bordered table-dark" id="backupContainerTable">
                          <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date Created</th>
                                <th>Restore</th>
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
<div class="row" id="backupContents">
    <div class="col-md-9">

    </div>
</div>
</div>
<script>

var currentBackups = [];
var currentContainerBackups = [];

function makeLineData(data){
    let dataByYearMonth = [];
    $.each(data, (year, months)=>{
        let data = [];
        let color = randomColor();

        $.each(months, function(index, month){
            data.splice(parseInt(index - 1), 0, month);
        });

        dataByYearMonth.push({
            label: `${year} Data`,
            fill: false,
            borderColor: color,
            pointHoverBackgroundColor: color,
            backgroundColor: color,
            pointHoverBorderColor: color,
            data: data
        });
    });
    return dataByYearMonth;
}

$(document).on("click", ".restoreBackup", function(){
    let backupId = $(this).parents("tr").data("backupId");
    restoreBackupContainerConfirm(
        backupId,
        currentContainerBackups.hostAlias,
        currentContainerBackups.container,
        loadBackupsOverview
    );
});

$(document).on("click", "#backToBackupGraphs", function(){
    $("#graphCards").show();
    $("#containerBackupDetails").hide();
});

$(document).on("click", ".viewContainerBackups", function(){
    $("#graphCards").hide();
    $("#containerBackupDetails").show();
    currentContainerBackups = currentBackups[$(this).data("hostAlias")].containers[$(this).data("containerIndex")];
    currentContainerBackups.hostAlias = $(this).data("hostAlias");
    currentContainerBackups.container = $(this).data("container");
    $("#backupContainerName").text(currentContainerBackups.name);

    let trs = "";

    if(currentContainerBackups.allBackups.length > 0){
        $.each(currentContainerBackups.allBackups, (_, backup)=>{
            trs += `<tr data-backup-id="${backup.id}">
                <td>${backup.name}</td>
                <td>${moment(backup.backupDateCreated).fromNow()}</td>
                <td><button class="btn btn-warning restoreBackup">Restore</button></td>
            </tr>`
        });
    }else{
        trs += `<tr>
            <td class="alert alert-danger text-center" colspan="999">No Backups</td>
        </tr>`
    }

    $("#backupContainerTable > tbody").empty().append(trs);
});

function loadBackupsView() {
    $(".boxSlide, #backupContents").hide();
    $("#backupsBox, #backupsOverview").show();
    $("#sidebar-ul").empty();
    $(".sidebar-lg-show").removeClass("sidebar-lg-show");
    loadBackupsOverview();
}

function loadBackupsOverview() {
    $("#graphCards").show();
    $("#containerBackupDetails").hide();
    ajaxRequest(globalUrls.backups.getOverview, {}, (data)=>{
        data = makeToastr(data);

        let backupTrs = "";

        currentBackups = data.allBackups;

        $.each(data.allBackups, (host, hostDetails)=>{
            backupTrs += `<tr class="bg-info"><td class="text-center" colspan="999">${host}</tr></tr>`;
            $.each(hostDetails.containers, (containerIndex, container)=>{

                let trClass = container.lastBackup.neverBackedUp || !container.containerExists ? "danger" : "success";

                let createdDate = container.containerExists ? moment(container.created).fromNow() : "Deleted";
                let date = "Never";
                let viewButton = `<button
                    class="btn btn-primary viewContainerBackups"
                    data-host-alias="${host}"
                    data-host-id="${hostDetails.hostId}"
                    data-container-index="${containerIndex}"
                    data-container="${container.name}">
                    View!
                </button>`;

                if(container.lastBackup.hasOwnProperty("backupDateCreated")){
                    date = moment(container.lastBackup.backupDateCreated).fromNow()
                }

                backupTrs += `<tr class="alert alert-${trClass}">
                    <td>${container.name}</td>
                    <td>${createdDate}</td>
                    <td>${date}</td>
                    <td>${viewButton}</td>
                </tr>`
            });
        })
        $("#containerBackupTable > tbody").empty().append(backupTrs);

        new Chart($("#backupsSizeChart"), {
            type: "line",
            data: {
                labels: monthsNameArray,
                datasets: makeLineData(data.sizeByMonthYear)
            },
            options: {
              cutoutPercentage: 40,
              responsive: false,
              scales: scalesBytesCallbacks,
              tooltips: toolTipsBytesCallbacks
            }
        });

        new Chart($("#backupFilesChart"), {
            type: "line",
            data: {
                labels: monthsNameArray,
                datasets: makeLineData(data.filesByMonthYear)
            },
            options: {
                scales: {
                    yAxes: [{
                      ticks: {
                        stepSize: 5
                      }
                    }]
                }
            }
        });
    });
}


</script>
