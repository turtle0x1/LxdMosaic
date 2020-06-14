<div id="overviewBox" class="boxSlide">
    <div class="row">
          <div class="col-lg-5">
              <div class="card bg-dark">
                  <div class="card-header">
                      <h4>Hosts</h4>
                  </div>
                  <div class="card-body table-responsive text-white">
                      <table class="table table-sm table-dark table-bordered" id="dashboardHostTable">
                          <thead>
                              <tr>
                                  <th> Name </th>
                                  <th> Project </th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
          <div class="col-lg-7">
              <div class="row">
                  <div class="col-md-6">
                      <div class="card bg-dark">
                          <div class="card-header">
                              <h4>Current Usage</h4>
                          </div>
                          <div class="card-body" id="currentMemoryUsageCardBody">
                          </div>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="card bg-dark">
                          <div class="card-header">
                              <h4> Memory Usage </h4>
                          </div>
                          <div class="card-body" >
                              <div class="alert alert-warning text-center notEnoughData">
                                  Not enough data check again in 10 minutes
                              </div>
                              <div id="dashboardMemoryHistoryBox">
                              </div>
                          </div>
                      </div>

                  </div>
              </div>
              <div class="row">
                  <div class="col-md-6">
                      <div class="card bg-dark">
                          <div class="card-header">
                              <h4> Running Instances </h4>
                          </div>
                          <div class="card-body" >
                              <div class="alert alert-warning text-center notEnoughData">
                                  Not enough data check again in 10 minutes
                              </div>
                              <div id="dashboardRunningInstancesBox">
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="card bg-dark">
                          <div class="card-header">
                              <h4>Storage Usage</h4>
                          </div>
                          <div class="card-body" >
                              <div class="alert alert-warning text-center notEnoughData">
                                  Not enough data check again in 10 minutes
                              </div>
                              <div id="dashboardStorageHistoryBox">
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
    </div>
</div>
</div>

<script>

$(document).on("click", "#createVm", function(){
    $("#modal-vms-create").modal("show");
});

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
