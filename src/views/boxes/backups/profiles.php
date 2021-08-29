<div id="profilesBackupOverview" clas="backupsOverviewBox" style="display: none;">
    <div class="row">
        <div class="col-md-6 border-end">
            <div class="card bg-dark text-white">
                <div class="card-header">
                    <h4>Project Profile Backups</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-dark" id="profileBackupTable">
                        <thead>
                            <tr>
                                <th>Project</th>
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
        <div class="col-md-6" id="projectProfileBackupGraph">
              <div class="card bg-dark text-white mb-2">
                  <div class="card-header">
                      <h4> Disk Usage </h4>
                  </div>
                  <div class="card-body">
                      <canvas style="width: 100%; max-height: 400px; width: 400px;" id="profileBackupsSizeChart"></canvas>
                  </div>
              </div>
              <div class="card bg-dark text-white">
                  <div class="card-header">
                      <h4> # Backup Files </h4>
                  </div>
                  <div class="card-body">
                      <canvas style="width: 100%; max-height: 400px; width: 400px;" id="profileBackupFilesChart"></canvas>
                  </div>
              </div>
        </div>
        <div class="col-md-6" id="projectProfilesBackupDetails">
              <div class="card bg-dark text-white">
                  <div class="card-header d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                      <h4>
                          <span id="backupProjectName"></span>
                          Backups
                      </h4>
                      <div class="btn-toolbar">
                        <div class="btn-group me-2">
                            <button data-toggle="tooltip" data-bs-placement="bottom" title="Schedule Backup" class="btn btn-outline-primary btn-sm" id="scheduleInstanceBackup">
                                <i class="fas fa-clock"></i>
                            </button>
                            <button data-toggle="tooltip" data-bs-placement="bottom" title="Disable Backup Schedule" class="btn btn-outline-warning btn-sm" id="disableInstanceSchedule">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <button data-toggle="tooltip" data-bs-placement="bottom" title="Back To Graphs" class="btn btn-outline-primary btn-sm" id="backToBackupGraphs">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </div>
                      </div>

                  </div>
                  <div class="card-body">
                      <div class="d-none text-center font-weight-bold mb-2" id="ghostInstanceText">
                          <i class="fas fa-ghost text-success me-2"></i>This instance has been deleted, this backup is all that remains!
                      </div>
                      <table class="table table-bordered table-dark" id="backupProjectTable">
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


<script>
function loadProfileBackupsOverview(){
    $("#projectProfileBackupGraph").show();
    $("#projectProfilesBackupDetails").hide();
    ajaxRequest(globalUrls.backups.profiles.getOverview, {}, (data)=>{
        data = makeToastr(data);

        let backupTrs = "";

        currentBackups = data.allBackups;
        if(data.allBackups.length == 0){
            backupTrs = `<tr>
                <td colspan="999" class="text-center"><i class="fas fa-info-circle text-info me-2"></i>No Projects / backups!</td>
            </tr>`;
        }else{
            $.each(data.allBackups, (host, hostDetails)=>{
                backupTrs += `<tr><td class="text-center text-success" colspan="999"><i class="fas fa-server me-2"></i>${host}</tr></tr>`;
                if(!hostDetails.supportsBackups){
                    backupTrs += `<tr><td colspan="999" class="text-center"><i class="fas fa-info-circle text-warning me-2"></i>Doesn't support backups!</td></tr>`
                    return true;
                }

                if(hostDetails.projects.length == 0){
                    backupTrs += `<tr><td colspan="999" class="text-center"><i class="fas fa-info-circle text-warning me-2"></i>No Instances In Project!</td></tr>`
                    return true;
                }

                $.each(hostDetails.projects, (projectIndex, project)=>{

                    let trClass = project.lastBackup.neverBackedUp ? "danger" : "success";

                    let date = "Never";
                    let viewButton = "Host Doesn't support backups";

                    let instanceName = project.name

                    let ghostIcon = '<i class="fas fa-ghost" data-toggle="tooltip" data-bs-placement="bottom" title="Instance Deleted" class="btn btn-outline-primary btn-sm"></i>'

                    if(project.projectExists){
                        instanceName += ghostIcon;
                    }else{
                        trClass = "success";
                    }

                    instanceName = `<a
                        href="#"
                        class="viewProjectProfileBackups"
                        data-host-alias="${host}"
                        data-host-id="${hostDetails.hostId}"
                        data-project-index="${projectIndex}"
                        data-project="${project.name}">
                        ${project.name} ${!project.projectExists ? ghostIcon : ""}
                    </a>`;

                    if(project.lastBackup.hasOwnProperty("storedDateCreated")){
                        date = moment.utc(project.lastBackup.storedDateCreated).local().fromNow()
                    }

                    let upToString = project.strategyName == "" ? "" :  ` / ${project.scheduleRetention}`;

                    backupTrs += `<tr>
                        <td>${instanceName}</td>
                        <td>${project.strategyName} ${project.scheduleString}</td>
                        <td class='text-${trClass}'>${date}</td>
                        <td>${project.allBackups.length} ${upToString}</td>
                        <td>${formatBytes(project.totalSize)}</td>
                    </tr>`
                });
            })
        }


        $("#profileBackupTable > tbody").empty().append(backupTrs);
        $("#profileBackupTable > tbody").find('[data-toggle="tooltip"]').tooltip()

        let sizeChartData = makeLineData(data.sizeByMonthYear, "Local File Size Usage");
        let filesChartData = makeLineData(data.filesByMonthYear, "# Local Backup Files");

        new Chart($("#profileBackupsSizeChart"), {
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

        new Chart($("#profileBackupFilesChart"), {
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


$(document).on("click", ".viewProjectProfileBackups", function(){
    $("#projectProfileBackupGraph").hide();
    $("#projectProfilesBackupDetails").show();
    currentTr = $(this).parents("tr");
    currentProjectProfileBackups = currentBackups[$(this).data("hostAlias")].projects[$(this).data("projectIndex")];
    console.log(currentProjectProfileBackups);
    currentProjectProfileBackups.hostId = $(this).data("hostId");
    currentProjectProfileBackups.hostAlias = $(this).data("hostAlias");
    currentProjectProfileBackups.project = $(this).data("project");
    $("#backupContainerName").text(currentProjectProfileBackups.name);

    let trs = "";

    if(currentProjectProfileBackups.containerExists == false){
        $("#ghostInstanceText").removeClass("d-none");
    }else{
        $("#ghostInstanceText").addClass("d-none");
    }

    if(currentProjectProfileBackups.allBackups.length > 0){
        $.each(currentProjectProfileBackups.allBackups, (_, backup)=>{
            trs += `<tr data-backup-id="${backup.id}">
                <td>${backup.localPath}</td>
                <td>${moment.utc(backup.storedDateCreated).local().fromNow()}</td>
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
            <td class="text-warning text-center" colspan="999"><i class="fas fa-info-circle me-2"></i>No Backups - Consider Scheduling Backups Above</td>
        </tr>`
    }

    $("#backupProjectTable > tbody").empty().append(trs);
});
</script>
