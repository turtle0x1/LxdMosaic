<div id="overviewBox" class="boxSlide">
    <div class="row" id="serverOverviewGraphs">
      <!-- /.col-->
          <div class="col-lg-6">
              <div class="card">
                  <div class="card-body">
                      <div class="alert alert-warning text-center notEnoughData">
                          <b> <u> Memory Ussage Chart </u> </b><br/>
                          Not enough data check again in 10 minutes
                      </div>
                      <canvas id="memoryUsage" height="200"></canvas>
                  </div>
              </div>
          </div>
          <div class="col-lg-6">
              <div class="card">
                  <div class="card-body">
                      <div class="alert alert-warning text-center notEnoughData">
                          <b> <u> Active Containers Chart </u> </b><br/>
                          Not enough data check again in 10 minutes
                      </div>
                      <canvas id="activeContainers" height="200"></canvas>
                  </div>
              </div>
          </div>
      <!-- /.col-->
    </div>
    <div class="row">
      <!-- /.col-->
      <div class="col-sm-6 col-lg-12" id="serverOverviewDetails">
      </div>
      <!-- /.col-->
    </div>
</div>

<script>

var emptyServerBox = function(){
    return $(`
    <div class="brand-card" id="">
      <div class="brand-card-header bg-twitter">
        <h4 class='host'></h4>
      </div>
      <div class="brand-card-body">
        <div>
          <div class="text-value">CPU <i class="fas fa-microchip"></i></div>
          <div class="text-uppercase text-muted cpuDetails">CPU Details</div>
        </div>
        <div>
          <div class="text-value">Memory <i class="fas fa-memory"></i></div>
          <div class="text-uppercase text-muted memory"></div>
        </div>
        <div class='gpuGroup d-md-down-none'>
          <div class="text-value">GPU's<i class="fab fa-megaport"></i></div>
          <div class="text-uppercase text-muted gpuDetails"></div>
        </div>
        <div>
            <div class="text-value">Project <i class="fas fa-project-diagram"></i></div>
            <div class="form-group projectFormGroup">
                <select class="form-control projects changeHostProject"></select>
            </div>
        </div>
        <div>
            <div class="text-value">Alias<i class="fas fa-passport"></i></div>
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
</script>

<?php
    require __DIR__ . "/../modals/hosts/editDetails.php";
?>
