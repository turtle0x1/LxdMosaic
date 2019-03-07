<div id="overviewBox" class="boxSlide">
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
    <div class="brand-card">
      <div class="brand-card-header bg-twitter">
        <h4 class='host'></h4>
      </div>
      <div class="brand-card-body">
        <div>
          <div class="text-value">CPU</div>
          <div class="text-uppercase text-muted cpuDetails">CPU Details</div>
        </div>
        <div>
          <div class="text-value">Memory</div>
          <div class="text-uppercase text-muted memory"></div>
        </div>
        <div>
            <div class="text-value">Project</div>
            <div class="form-group projectFormGroup">
                <select class="form-control projects changeHostProject"></select>
            </div>

        </div>
      </div>
    </div>`);
}

$("#overviewBox").on("click", "#createContainer", function(){
    $("#modal-container-create").modal("show");
});
</script>
