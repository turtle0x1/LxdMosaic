<div id="backupsBox" class="boxSlide">
<div id="backupsOverview" class="row">
    <div class="col-md-4">
          <div class="card bg-dark">
              <div class="card-header">
                  <h4> Backup Size On Disk When Stored </h4>
              </div>
              <div class="card-body">
                  <canvas style="width: 100%; height: 400px; width: 400px;" id="backupsSizeChart"></canvas>
              </div>
          </div>
    </div>
    <div class="col-md-4">
          <div class="card bg-dark">
              <div class="card-header">
                  <h4> Total Files Stored </h4>
              </div>
              <div class="card-body">
                  <canvas style="width: 100%; height: 400px; width: 400px;" id="backupFilesChart"></canvas>
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

function loadBackupsView() {
    $(".boxSlide, #backupContents").hide();
    $("#backupsBox, #backupsOverview").show();
    $("#sidebar-ul").empty();
    $(".sidebar-lg-show").removeClass("sidebar-lg-show");

    ajaxRequest(globalUrls.backups.getOverview, {}, (data)=>{
        data = makeToastr(data);

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
