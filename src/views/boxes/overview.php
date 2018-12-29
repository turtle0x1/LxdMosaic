<div id="overviewBox" class="boxSlide">
<!-- <h4> Container: <`span id="containerName"></span> </h4> -->
<div class="col-md-12">
    <div class="card">
      <div class="card-header" role="tab" id="container-actionsHeading">
        <h5>
          <a data-toggle="collapse" data-parent="#accordion" href="#container-actionsCollapse" aria-expanded="true" aria-controls="container-actionsCollapse">
            Server Overview
          </a>
          <button class="btn btn-primary pull-right" id="createContainer"> Create Container </button>
        </h5>
      </div>

      <div id="container-actionsCollapse" class="collapsed show" aria-expanded="true" role="tabpanel" aria-labelledby="container-actionsHeading">
        <div id="serverOverviewDetails" class="card-block">

        </div>
      </div>
    </div>
</div>
</div>

<script>
$("#overviewBox").on("click", "#createContainer", function(){
    $("#modal-container-create").modal("show");
});
</script>
