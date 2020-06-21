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
                                <th># Backups</th>
                                <th>Size On Disk</th>
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
                                <th>Size On Disk</th>
                                <th>Restore</th>
                                <th>Delete</th>
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

var currentTr = null;
var currentBackups = [];
var currentContainerBackups = [];
const monthList = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug", "Sep", "Oct", "Nov", "Dec"];

function makeLineData(data, setLabel){
    let dataByYearMonth = [];
    let labels = [];
    let outData = [];

    $.each(data, (year, months)=>{
        $.each(months, (month, value)=>{
            labels.push(monthList[month - 1] + " " + year);
            outData.push(value);
        });
    });

    let color = randomColor();

    dataByYearMonth.push({
        label: setLabel,
        fill: false,
        borderColor: color,
        pointHoverBackgroundColor: color,
        backgroundColor: color,
        pointHoverBorderColor: color,
        data: outData
    });

    return {
        labels: labels,
        data: dataByYearMonth
    };
}

$(document).on("click", ".deleteBackup", function(){
    let tr = $(this).parents("tr");
    let backupId = tr.data("backupId");
    let x = {backupId: backupId};

    ajaxRequest(globalUrls.instances.backups.delete, x, (data)=>{
        data = makeToastr(data);

        if(data.state == "error"){
            return false;
        }

        tr.remove();
    });

});

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
                text: "Disable Schedule",
                action: function(){

                    let x = {
                        hostId: currentContainerBackups.hostId,
                        instance: currentContainerBackups.name
                    }

                    ajaxRequest(globalUrls.instances.backups.disabledSchedule, x, (data)=>{
                        makeToastr(data);
                        if(data.state == "error"){
                            return false;
                        }
                        currentTr.find("td:eq(1)").text("");
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
            <div class="alert alert-warning">
                <b>Be careful backups runs in parallel!</b> If you schedule lots
                at once LXD itself (and therefor LXDMosaic) may become
                unresponsive while the backups run!
            </div>
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
            <div class="form-group">
                <label>Number Of Backups To Keep</label>
                <input value="7" class="form-control" id="scheduleRetention"/>
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
                    let retentionInput = $("#scheduleRetention");
                    let frequency = frequencyInput.val();
                    let time = timeInput.val();
                    let strategy = strategyInput.val();
                    let retention = retentionInput.val();

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
                    } else if(!$.isNumeric(retention)) {
                        $.alert("Number of backups to keep must be a number");
                        retentionInput.focus();
                        return false;
                    }

                    let x = {
                        frequency: frequency,
                        time: time,
                        strategy: strategy,
                        hostId: currentContainerBackups.hostId,
                        instance: currentContainerBackups.name,
                        retention: retention
                    }

                    ajaxRequest(globalUrls.instances.backups.schedule, x, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            return false;
                        }
                        currentTr.find("td:eq(1)").text(strategy + " " + frequency + " " + time);
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
    currentTr = $(this).parents("tr");
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
                <td>${formatBytes(backup.filesize)}</td>
                <td>
                    <button class="btn btn-sm btn-outline-warning restoreBackup">
                        <i class="fas fa-trash-restore"></i>
                    </button>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-danger deleteBackup">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
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

                let ghostIcon = '<i class="fas fa-ghost" data-toggle="tooltip" data-placement="bottom" title="Instance Deleted" class="btn btn-outline-primary btn-sm"></i>'

                if(container.containerExists){
                    instanceName += ghostIcon;
                }

                if(hostDetails.supportsBackups){
                    instanceName = `<a
                        href="#"
                        class="viewContainerBackups"
                        data-host-alias="${host}"
                        data-host-id="${hostDetails.hostId}"
                        data-container-index="${containerIndex}"
                        data-container="${container.name}">
                        ${container.name} ${!container.containerExists ? ghostIcon : ""}
                    </a>`;
                }


                if(container.lastBackup.hasOwnProperty("backupDateCreated")){
                    date = moment(container.lastBackup.backupDateCreated).fromNow()
                }

                let upToString = container.strategyName == "" ? "" :  ` / ${container.scheduleRetention} scheduled to keep`;

                backupTrs += `<tr class="alert alert-${trClass}">
                    <td>${instanceName}</td>
                    <td>${container.strategyName} ${container.scheduleString}</td>
                    <td>${date}</td>
                    <td>${container.allBackups.length} ${upToString}</td>
                    <td>${formatBytes(container.totalSize)}</td>
                </tr>`
            });
        })
        $("#containerBackupTable > tbody").empty().append(backupTrs);
        $("#containerBackupTable > tbody").find('[data-toggle="tooltip"]').tooltip()

        let sizeChartData = makeLineData(data.sizeByMonthYear, "Local File Size Usage");
        let filesChartData = makeLineData(data.filesByMonthYear, "# Local Backup Files");

        new Chart($("#backupsSizeChart"), {
            type: "line",
            data: {
                labels: sizeChartData.labels,
                datasets: sizeChartData.data
            },
            options: {
              cutoutPercentage: 40,
              scales: scalesBytesCallbacks,
              tooltips: toolTipsBytesCallbacks
            }
        });

        new Chart($("#backupFilesChart"), {
            type: "line",
            data: {
                labels: filesChartData.labels,
                datasets: filesChartData.data
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
