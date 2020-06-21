<div id="backupsBox" class="boxSlide">
<div id="backupsOverview">
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-dark">
                <div class="card-header">
                    <h4>Instance Backups</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-dark" id="containerBackupTable">
                        <thead>
                            <tr>
                                <th>Instance</th>
                                <th>Schedule</th>
                                <th>Last Backed Up</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6" id="graphCards">
              <div class="card bg-dark">
                  <div class="card-header">
                      <h4> Disk Usage </h4>
                  </div>
                  <div class="card-body">
                      <canvas style="width: 100%; max-height: 400px; width: 400px;" id="backupsSizeChart"></canvas>
                  </div>
              </div>
              <div class="card bg-dark">
                  <div class="card-header">
                      <h4> # Backup Files </h4>
                  </div>
                  <div class="card-body">
                      <canvas style="width: 100%; max-height: 400px; width: 400px;" id="backupFilesChart"></canvas>
                  </div>
              </div>
        </div>
        <div class="col-md-6" id="containerBackupDetails">
              <div class="card bg-dark">
                  <div class="card-header d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                      <h4>
                          <span id="backupContainerName"></span>
                          Backups
                      </h4>
                      <div class="btn-toolbar">
                        <div class="btn-group mr-2">
                            <button data-toggle="tooltip" data-placement="bottom" title="Schedule Backup" class="btn btn-outline-primary btn-sm" id="scheduleInstanceBackup">
                                <i class="fas fa-clock"></i>
                            </button>
                            <button data-toggle="tooltip" data-placement="bottom" title="Disable Backup Schedule" class="btn btn-outline-warning btn-sm" id="disableInstanceSchedule">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <button data-toggle="tooltip" data-placement="bottom" title="Back To Graphs" class="btn btn-outline-primary btn-sm" id="backToBackupGraphs">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </div>
                      </div>

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

$(document).on("click", "#disableInstanceSchedule", function(){
    $.confirm({
        title: `Disable Instance Backup Schedule`,
        content: ``,
        buttons: {
            cancel: {
                btnClass: "btn btn-secondary",
                text: "cancel"
            },
            ok: {
                btnClass: "btn btn-danger",
                text: "Schedule",
                action: function(){

                    let x = {
                        hostId: currentContainerBackups.hostId,
                        instance: currentContainerBackups.name
                    }

                    ajaxRequest(globalUrls.instances.backups.disabledSchedule, x, (data)=>{
                        makeToastr(data)
                    });
                }
            }
        }
    });
});
$(document).on("click", "#scheduleInstanceBackup", function(){
    $.confirm({
        title: `Schedule Instance Backup`,
        content: `
            <div class="form-group">
                <label>Frequency</label>
                <select class="form-control" id="scheduleBackupFrequency">
                    <option value=""></option>
                    <option value="daily">Daily</option>
                </select>
            </div>
            <div class="form-group">
                <label>24 Hour Time (00:00-23:59)</label>
                <input class="form-control" id="scheduleBackupTime" />
            </div>
            <div class="form-group">
                <label>Strategy</label>
                <select class="form-control" id="scheduleBackupStrategy">
                </select>
            </div>
        `,
        buttons: {
            cancel: {
                btnClass: "btn btn-secondary",
                text: "cancel"
            },
            ok: {
                btnClass: "btn btn-primary",
                text: "Schedule",
                action: function(){
                    let frequencyInput = $("#scheduleBackupFrequency");
                    let timeInput = $("#scheduleBackupTime");
                    let strategyInput = $("#scheduleBackupStrategy");
                    let frequency = frequencyInput.val();
                    let time = timeInput.val();
                    let strategy = strategyInput.val();

                    if(frequency == ""){
                        $.alert("Select frequency")
                        frequencyInput.focus()
                        return false;
                    } else if (time == ""){
                        $.alert("Select backup time")
                        timeInput.focus()
                        return false;
                    } else if(!$.isNumeric(strategy)) {
                        $.alert("Select strategy")
                        strategyInput.focus()
                        return false;
                    }

                    let x = {
                        frequency: frequency,
                        time: time,
                        strategy: strategy,
                        hostId: currentContainerBackups.hostId,
                        instance: currentContainerBackups.name
                    }

                    ajaxRequest(globalUrls.instances.backups.schedule, x, (data)=>{
                        makeToastr(data)
                    });
                }
            }
        },
        onContentReady: function(){
            let content = this.$content;
            content.find("#scheduleBackupTime").timepicker({
                timeFormat: 'HH:mm',
                interval: 30,
                minTime: '00:00',
                maxTime: '23:59',
                defaultTime: '21:00',
                startTime: '00:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
            ajaxRequest(globalUrls.backups.strategies.getAll, {}, (data)=>{
                data = makeToastr(data);
                let options = "<option value=''>Please select</option>";
                $.each(data, (_, strategy)=>{
                    options += `<option value='${strategy.id}'>${strategy.name}</option>`;
                });
                content.find("#scheduleBackupStrategy").append(options);
            })
        }
    });
});

$(document).on("keyup", "#scheduleBackupTime", function(){
    $("#scheduleBackupTime").filter(function(value) {
        return /^-?\d*[.,]?\d{0,2}$/.test(value);
    });
})

$(document).on("click", "#backToBackupGraphs", function(){
    $("#graphCards").show();
    $("#containerBackupDetails").hide();
});

$(document).on("click", ".viewContainerBackups", function(){
    $("#graphCards").hide();
    $("#containerBackupDetails").show();
    currentContainerBackups = currentBackups[$(this).data("hostAlias")].containers[$(this).data("containerIndex")];
    currentContainerBackups.hostId = $(this).data("hostId");
    currentContainerBackups.hostAlias = $(this).data("hostAlias");
    currentContainerBackups.container = $(this).data("container");
    $("#backupContainerName").text(currentContainerBackups.name);

    let trs = "";

    if(currentContainerBackups.allBackups.length > 0){
        $.each(currentContainerBackups.allBackups, (_, backup)=>{
            trs += `<tr data-backup-id="${backup.id}">
                <td>${backup.name}</td>
                <td>${moment(backup.backupDateCreated).fromNow()}</td>
                <td><button class="btn btn-sm btn-outline-warning restoreBackup">Restore</button></td>
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
            backupTrs += `<tr class="bg-success"><td class="text-center" colspan="999">${host}</tr></tr>`;
            $.each(hostDetails.containers, (containerIndex, container)=>{

                let trClass = container.lastBackup.neverBackedUp || !container.containerExists ? "danger" : "success";

                let createdDate = container.containerExists ? moment(container.created).fromNow() : "Deleted";
                let date = "Never";
                let viewButton = "Host Doesn't support backups";

                let instanceName = container.name

                if(hostDetails.supportsBackups){
                    instanceName = `<a
                        href="#"
                        class="viewContainerBackups"
                        data-host-alias="${host}"
                        data-host-id="${hostDetails.hostId}"
                        data-container-index="${containerIndex}"
                        data-container="${container.name}">
                        ${container.name}
                    </a>`;
                }


                if(container.lastBackup.hasOwnProperty("backupDateCreated")){
                    date = moment(container.lastBackup.backupDateCreated).fromNow()
                }

                backupTrs += `<tr class="alert alert-${trClass}">
                    <td>${instanceName}</td>
                    <td>${container.scheduleString}</td>
                    <td>${date}</td>
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
