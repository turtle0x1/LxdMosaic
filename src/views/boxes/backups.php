<div id="backupsBox" class="boxSlide">
<div id="backupsOverview">
    <div class="row">
    <div class="col-md-4">
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
    <div class="col-md-8">
        <div class="card bg-dark">
            <div class="card-header">
                <h4>Host Cotntainers Local Backups</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-dark" id="containerBackupTable">
                    <thead>
                        <tr>
                            <th>Container</th>
                            <th>Created</th>
                            <th>Last Backed Up</th>
                            <th>Backup</th>
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

$(document).on("click", ".createBackup", function(){
    backupContainerConfirm(
        $(this).data("hostId"),
        $(this).data("hostAlias"),
        $(this).data("container"),
        loadBackupsOverview
    );
});

function loadBackupsView() {
    $(".boxSlide, #backupContents").hide();
    $("#backupsBox, #backupsOverview").show();
    $("#sidebar-ul").empty();
    $(".sidebar-lg-show").removeClass("sidebar-lg-show");
    loadBackupsOverview();
}

function loadBackupsOverview() {
    ajaxRequest(globalUrls.backups.getOverview, {}, (data)=>{
        data = makeToastr(data);

        let backupTrs = "";
        $.each(data.noBackups, (host, hostDetails)=>{
            backupTrs += `<tr class="bg-info"><td class="text-center" colspan="999">${host}</tr></tr>`;
            $.each(hostDetails.containers, (_, container)=>{
                let trClass = container.lastBackup.neverBackedUp ? "danger" : "success";

                let date = "Never";

                if(container.lastBackup.hasOwnProperty("backupDateCreated")){
                    date = moment(container.lastBackup.backupDateCreated).fromNow()
                }

                backupTrs += `<tr class="alert alert-${trClass}">
                    <td>${container.name}</td>
                    <td>${moment(container.created).fromNow()}</td>
                    <td>${date}</td>
                    <td><button
                        class="btn btn-primary createBackup"
                        data-host-alias="${hostDetails.host}"
                        data-host-id="${hostDetails.hostId}"
                        data-container="${container.name}">
                        Backup!
                    </button>
                    </td>
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
