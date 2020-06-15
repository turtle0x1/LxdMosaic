<div id="overviewBox" class="boxSlide">
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                <ul class="nav nav-tabs" id="userDashboardsList">
                </ul>
                <button class="btn btn-outline-primary" id="newDashboardBtn" data-toggle="tooltip" data-placement="bottom" title="Create dashboard">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="row mb-2" id="userDashboard">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                <div></div>
                <div class="btn-toolbar float-right">
                    <button class="btn btn-outline-primary" data-toggle="tooltip" data-placement="bottom" title="Add graph" id="addDashMetricGraph">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="btn btn-outline-primary" data-toggle="tooltip" data-placement="bottom" title="Edit Dashboard" id="editDashboardGraphs">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger" data-toggle="tooltip" data-placement="bottom" title="Delete Dashboard" id="deleteDashboard">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card-columns" id="userDashboardGraphs">
            </div>
        </div>
    </div>
    <div class="row mb-2" id="generalDashboard">
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
var tempObjs = {};
var currentDashboard = null

$(document).on("click", "#editDashboardGraphs", function(){
    if($(this).hasClass("btn-primary")){
        $(this).removeClass("btn-primary").addClass("btn-outline-primary");
        $(".removeDashGraph").hide()
    }else{
        $(this).removeClass("btn-outline-primary").addClass("btn-primary");
        $(".removeDashGraph").show()
    }
});

function loadUserDashboardGraphs()
{
    $("#userDashboardGraphs").empty();
        let x = {dashboardId: currentDashboard};
    ajaxRequest(globalUrls.user.dashboard.get, x, (data)=>{
        data = makeToastr(data);

        $.each(data.graphsData, (i, graph)=>{
            let x = $(`<div class="card bg-dark" id="${graph.graphId}">
                <div class="card-header">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                        <h5>${graph.graphName}</h5>
                        <button class="btn btn-outline-danger btn-sm removeDashGraph" style="display: none">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas height="150" id="${i}"></canvas>

                </div>
            </div>`);
            let scales = graph.formatBytes ? scalesBytesCallbacks : [];
            let tooltips = graph.formatBytes ? toolTipsBytesCallbacks : [];
            let color = randomColor();
            new Chart(x.find("#" +i), {
                type: "line",
                data: {
                    labels: graph.labels,
                    datasets: [{
                        label: `Data`,
                        fill: false,
                        borderColor: color,
                        pointHoverBackgroundColor: color,
                        backgroundColor: color,
                        pointHoverBorderColor: color,
                        data: graph.data
                    }]
                },
                options: {
                  legend: {
                    display: false
                  },

                  cutoutPercentage: 40,
                  responsive: true,
                  scales: scales,
                  tooltips: tooltips
                }
            });
            $("#userDashboardGraphs").append(x);
        })
    });
}

$(document).on("click", ".viewDashboard", function(){
    $("#userDashboardsList").find(".active").removeClass("active");
    $(this).addClass("active");
    $("#editDashboardGraphs").removeClass("btn-primary").addClass("btn-outline-primary");

    let dashId = $(this).attr("id");
    if(dashId == "generalDashboardLink"){
        $("#generalDashboard").show();
        $("#userDashboard").hide();
        $("#userDashboardGraphs").empty();
        currentDashboard = null;
        return false;
    }
    currentDashboard = dashId;
    $("#generalDashboard").hide();
    $("#userDashboard").show();
    loadUserDashboardGraphs()
});

$(document).on("click", "#newDashboardBtn", function(){
    $.confirm({
        title: "Create a Dashboard",
        content: `<div class="form-group">
            <label>Name</label>
            <input class="form-control" name="name" />
        </div>`,
        buttons: {
            cancel: {},
            create: {
                btnClass: "btn-primary",
                action: function(){
                    let content = this.$content;
                    let dashNameInput = content.find("input[name=name]");
                    let dashName = dashNameInput.val().trim();
                    if(dashName == ""){
                        $.alert("Please provide dashboard name");
                        return false;
                    }

                    let x = {name: dashName};
                    ajaxRequest(globalUrls.user.dashboard.create, x, (data)=>{
                        console.log(data);
                    });
                }
            }
        }
    });
});

$(document).on("click", ".removeDashGraph", function(){
    let card = $(this).parents(".card");
    let graphId = card.attr("id");
    $.confirm({
        title: "Remove from dashobard?!",
        content: '',
        buttons: {
            cancel: {},
            delete: {
                btnClass: "btn-danger",
                action: function(){
                    let x = {graphId: graphId};
                    ajaxRequest(globalUrls.user.dashboard.graphs.delete, x, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            return false;
                        }
                        card.remove();
                    });
                }
            }
        }
    });
});

$(document).on("click", "#deleteDashboard", function(){
    $.confirm({
        title: "Delete dashobard?!",
        content: '',
        buttons: {
            cancel: {},
            delete: {
                btnClass: "btn-danger",
                action: function(){
                    let x = {dashboardId: currentDashboard};
                    ajaxRequest(globalUrls.user.dashboard.delete, x, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            return false;
                        }
                        $("#userDashboardsList").find("#generalDashboardLink").trigger("click");
                    });
                }
            }
        }
    });
});

$(document).on("click", "#addDashMetricGraph", function(){
    $.confirm({
        title: "Add A Graph",
        content: `
            <div class="form-group">
                <label>Graph</label>
                <input class="form-control" name="name" />
            </div>
            <div class="form-group">
                <label>Instance</label>
                <select id="addDashMetricInstanceSelect" class="form-control">
                </select>
            </div>
            <div class="form-group">
                <label>Metric</label>
                <select id="addDashMetricMetricSelect" class="form-control disabled" disabled>
                    <option value=''>Select Instance</option>
                </select>
            </div>
            <div class="form-group">
                <label>Filter</label>
                <select id="addDashMetricFilterSelect" class="form-control disabled" disabled>
                    <option value=''>Select Metric</option>
                </select>
            </div>
        `,
        buttons: {
            cancel: {},
            create: {
                btnClass: "btn-primary",
                action: function(){
                    let nameInput = this.$content.find("input[name=name]");
                    let instanceInput = $("#addDashMetricInstanceSelect");
                    let metricInput = $("#addDashMetricMetricSelect");
                    let filterInput = $("#addDashMetricFilterSelect");
                    let name = nameInput.val().trim();
                    let instance = instanceInput.val().trim();
                    let metric = metricInput.val().trim();
                    let filter = filterInput.val().trim();

                    if(name == ""){
                        nameInput.focus();
                        $.alert("Please input name");
                        return false;
                    } else if(instance == ""){
                        instanceInput.focus();
                        $.alert("Please select instance");
                        return false;
                    }else if(!$.isNumeric(metric)){
                        metricInput.focus();
                        $.alert("Please select meteric");
                        return false;
                    }else if(filter == ""){
                        filterInput.focus();
                        $.alert("Please select filter");
                        return false;
                    }

                    let x = {
                        dashboardId: currentDashboard,
                        name: name,
                        hostId: tempObjs[$("#addDashMetricInstanceSelect").find(":selected").data()["host"]].hostId,
                        instance: instance,
                        metricId: metric,
                        filter: filter,
                    }


                    ajaxRequest(globalUrls.user.dashboard.graphs.add, x, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            return false;
                        }
                        loadUserDashboardGraphs();
                    });

                }
            }
        },
        onContentReady: function(){
            let content = this.$content;
            ajaxRequest(globalUrls.instances.metrics.getAllAvailableMetrics, {}, (data)=>{

                data = makeToastr(data);
                tempObjs = data;
                let options = "<option value=''>Please select</option>";
                $.each(data, (host, details)=>{
                    $.each(details.instances, (instance, _)=>{
                        options += `<option data-host="${host}" value='${instance}'>${instance}</option>`;
                    });
                });
                content.find("#addDashMetricInstanceSelect").append(options);
            })
        }
    });
});

$(document).on("change", "#addDashMetricInstanceSelect", function(){
    if($(this).val() == ""){
        $("#addDashMetricMetricSelect").empty().append(`<option value=''>Select Instance</option>`).attr("disabled", true);
        $("#addDashMetricFilterSelect").empty().append(`<option value=''>Select Metric</option>`).attr("disabled", true);
        return false;
    }
    let metrics = tempObjs[$(this).find(":selected").data()["host"]].instances[$(this).val()];
    $("#addDashMetricMetricSelect").attr("disabled", false);
    let opts = "<option value=''>Please Select</option>";
    $.each(metrics, (_, metric)=>{
        opts += `<option value='${metric.metricId}'>${metric.metric}</option>`
    });
    $("#addDashMetricMetricSelect").empty().append(opts);

});

$(document).on("change", "#addDashMetricMetricSelect", function(){
    let type = $(this).val();
    if(type == ""){
        $("#addDashMetricFilterSelect").empty().append(`<option value=''>Select Metric</option>`).attr("disabled", true);
        $("#metricGraphBody").empty();
        return false;
    }
    let x = {
        type: type,
        hostId: tempObjs[$("#addDashMetricInstanceSelect").find(":selected").data()["host"]].hostId,
        container: $("#addDashMetricInstanceSelect").val()
    }
    ajaxRequest(globalUrls.instances.metrics.getTypeFilters, x, (data)=>{
        data = $.parseJSON(data);
        let html = "<option value=''>Please select</option>";
        $.each(data, (_, filter)=>{
            html += `<option value='${filter}'>${filter}</option>`
        });
        $("#addDashMetricFilterSelect").attr("disabled", false).empty().append(html);
    });
});

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
