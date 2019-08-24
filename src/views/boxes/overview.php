<div id="overviewBox" class="boxSlide">
    <div class="row" id="serverOverviewGraphs">
      <!-- /.col-->
          <div class="col-lg-4">
              <div class="card">
                  <div class="card-body bg-dark">
                      <div class="alert alert-warning text-center notEnoughData">
                          <b> <u> Memory Ussage Chart </u> </b><br/>
                          Not enough data check again in 10 minutes
                      </div>
                      <canvas id="memoryUsage" height="200"></canvas>
                  </div>
              </div>
          </div>
          <div class="col-lg-4">
              <div class="card">
                  <div class="card-body bg-dark">
                      <div class="alert alert-warning text-center notEnoughData">
                          <b> <u> Active Containers Chart </u> </b><br/>
                          Not enough data check again in 10 minutes
                      </div>
                      <canvas id="activeContainers" height="200"></canvas>
                  </div>
              </div>
          </div>
          <div class="col-lg-4">
              <div class="card">
                  <div class="card-body bg-dark">
                      <div class="alert alert-warning text-center notEnoughData">
                          <b> <u> Total Storage Used </u> </b><br/>
                          Not enough data check again in 10 minutes
                      </div>
                      <canvas id="totalStorageUsage" height="200"></canvas>
                  </div>
              </div>
          </div>
      <!-- /.col-->
    </div>
    <div class="row">
      <!-- /.col-->
      <div class="col-sm-12 col-lg-12" id="serverOverviewDetails">
      </div>
      <!-- /.col-->
    </div>
</div>

<script>

var emptyServerBox = function(){
    return $(`
    <div class="brand-card" id="">
      <div class="brand-card-header bg-info">
            <h4 class="host"></h4>
            <button class="btn btn-sm btn-danger deleteHost pull-right">
                <span class="fas fa-trash"></span>
            </button>
      </div>
      <div class="brand-card-body bg-dark text-white">
        <div>
          <div class="text-value text-white">CPU <i class="fas fa-microchip"></i></div>
          <div class="text-uppercase text-muted cpuDetails">CPU Details</div>
        </div>
        <div>
          <div class="text-value text-white">Memory <i class="fas fa-memory"></i></div>
          <div class="text-uppercase text-muted memory"></div>
        </div>
        <div class='gpuGroup d-md-down-none'>
          <div class="text-value text-white">GPU's<i class="fab fa-megaport"></i></div>
          <div class="text-uppercase text-muted gpuDetails text-white"></div>
        </div>
        <div>
            <div class="text-value text-white">Project <i class="fas fa-project-diagram"></i></div>
            <div class="form-group projectFormGroup">
                <select class="form-control projects changeHostProject"></select>
            </div>
        </div>
        <div>
            <div class="text-value text-white">Alias<i class="fas fa-passport"></i></div>
                <button class="btn btn-xs-sm btn-primary editHost pull-right"><i class="fas fa-pencil-alt"></i></button>
            </div>
        </div>

      </div>
    </div>`);
}

$(document).on("click", "#createContainer", function(){
    $("#modal-container-create").modal("show");
});

$(document).on("click", ".editHost", function(){
    editHostDetailsObj.hostId = $(this).parents(".brand-card").attr("id");
    $("#modal-hosts-edit").modal("show");
});

$(document).on("click", ".deleteHost", function(){
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
                    ajaxRequest(globalUrls.hosts.delete, {hostId: hostId}, (data)=>{
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

<?php
    require __DIR__ . "/../modals/hosts/editDetails.php";
?>
