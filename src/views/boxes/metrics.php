<div id="metricsBox" class="boxSlide">
    <div id="metricsOverview" class="row">
        <div class="col-md-12">
              <div class="card">
                <div class="card-header bg-info" role="tab" >
                  <h5>
                    <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#currentSettingsTable" aria-expanded="true" aria-controls="currentSettingsTable">
                      Metrics Overview
                    </a>
                  </h5>
                </div>
                <div id="currentSettingsTable" class="collapse in show" role="tabpanel" >
                  <div class="card-block bg-dark">
                    <table class="table table-dark table-bordered" id="metricInstanceTable">
                        <thead>
                            <tr>
                                <th>Instance</th>
                                <th>Pull Metrics</th>
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
</div>

<script>

function loadMetricsView()
{
    $(".boxSlide").hide();
    $("#metricsOverview, #metricsBox").show();
    $(".sidebar-lg-show").removeClass("sidebar-lg-show");
    setBreadcrumb("Metrics", "viewMetricSettings active");

    ajaxRequest(globalUrls.instances.metrics.getAllInstancesSettings, {}, (data)=>{
        data = $.parseJSON(data);
        let html = "";
        $.each(data.standalone.members, (_, host)=>{
            html += `<tr><td class="bg-primary text-center text-white" colspan="999">${host.alias}</td></tr>`;
            $.each(host.instances, (_, instance)=>{
                let pm = instance.pullMetrics ? "<i class='fas fa-check'></i>" : `<button data-instance="${instance.name}" data-host-id="${host.hostId}" class='btn btn-warning enablePullGathering'>Enable</button>`;
                html += `<tr>
                        <td>${instance.name}</td>
                        <td>${pm}</td>
                    </tr>`
            });
        });
        $("#metricInstanceTable > tbody").empty().append(html);
    });
}

$("#metricInstanceTable").on("click", ".enablePullGathering", function(){
    let btn = $(this);
    let td = btn.parents("td");
    btn.attr("disabled", true);
    let x = btn.data();

    ajaxRequest(globalUrls.instances.metrics.enablePullGathering, x, (data)=>{
        data = makeToastr(data);
        if(data.state == "error"){
            btn.attr("disabled", false);
            return false;
        }
        td.empty().append("<i class='fas fa-check'></i>");
    });
});
</script>
