<div id="overviewBox" class="boxSlide">
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom">
                <ul class="nav nav-tabs w-100" id="userDashboardsList" style="border: none !important;">
                </ul>
                <button class="btn btn-outline-primary" id="newDashboardBtn" data-toggle="tooltip" data-bs-placement="bottom" title="Create dashboard">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="row mb-2" id="userDashboard">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                <div id="dashboardTitle"></div>
                <div class="btn-toolbar float-end">
                    <button class="btn btn-outline-primary" data-toggle="tooltip" data-bs-placement="bottom" title="Add graph" id="addDashMetricGraph">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="btn btn-outline-primary" data-toggle="tooltip" data-bs-placement="bottom" title="Edit Dashboard" id="editDashboardGraphs">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger" data-toggle="tooltip" data-bs-placement="bottom" title="Delete Dashboard" id="deleteDashboard">
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
    <div class="mb-2" id="projectAnalyticsDashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">

                        <h4><i class="fas fa-chart-bar me-2"></i>Project Analytics</h4>
                        <div class="input-group mb-3 w-25">
                          <span class="input-group-text"><i class="fas fa-filter"></i></span>
                          <input type="text" class="form-control" placeholder="Filter Projects..." value="" id="filterDashProjectAnalyticsProject">
                        </div>
                </div>
                <div id="overviewGraphs">
                </div>
            </div>
        </div>
    </div>
    <div class="mb-2" id="generalDashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                        <h4><i class="fas fa-chart-bar me-2"></i>Overview</h4>
                        <div class="input-group mb-3 w-25">
                          <span class="input-group-text"><i class="fas fa-history"></i></span>
                          <select class="form-select" id="overviewHistoryDuration">
                                <option value="-30 mins" selected>30 Minutes</otion>
                                <option value="-1 hour">1 Hour</otion>
                                <option value="-3 hours">3 Hours</otion>
                                <option value="-6 hours">6 Hours</otion>
                                <option value="-12 hours">12 Hours</otion>
                                <option value="-1 day">1 Day</otion>
                                <option value="-3 day">3 Days</otion>
                                <option value="-1 week">1 Week</otion>
                                <option value="-2 week">2 Weeks</otion>
                                <option value="-1 month">1 Month</otion>
                          </select>
                        </div>
                </div>
                <div id="overviewGraphs">
                </div>
            </div>
        </div>
          <div class="row">
              <div class="col-lg-12">
                  <div id="totalsGraphs">
                  </div>
              </div>
          </div>

      </div>
</div>

<script>
var tempObjs = {};
var currentDashboard = null
var dashboardRefreshInterval = null;

$(document).on("click", "#editDashboardGraphs", function(){
    if($(this).hasClass("btn-primary")){
        $(this).removeClass("btn-primary").addClass("btn-outline-primary");
        $(".removeDashGraph").hide()
    }else{
        $(this).removeClass("btn-outline-primary").addClass("btn-primary");
        $(".removeDashGraph").show()
    }
});

$(document).on("change", "#overviewHistoryDuration", function(e){
    $("#totalsGraphs").empty().append(`<h4 class="text-center"><i class="fas fa-cog fa-spin"></i></h4>`)
    ajaxRequest('/api/ProjectAnalytics/GetGraphableProjectAnalyticsController/get', {history: $(this).val()}, (data)=>{
        let y = $(`<div class="row mb-2" ></div>`)
        let displayItems = {
            "Instances": {
                formatBytes: false,
                icon: 'fas fa-box'
            },
            "Disk": {
                formatBytes: true,
                icon: 'fas fa-hdd'
            },
            "Memory": {
                formatBytes: true,
                icon: 'fas fa-memory'
            },
            "Processes": {
                formatBytes: false,
                icon: 'fas fa-microchip'
            }
        }
        data = makeToastr(data)
        $.each(displayItems, (title, config)=>{


            let labels = [];
            let values = [];
            let limits = [];

            let cId = title.toLowerCase();

            $.each(data.totals[title], (created, value)=>{
                labels.push(moment.utc(created).local().format("HH:mm"))
                values.push(value)
            });

            var totalUsage = values.reduce(function(a, b){
                return parseInt(a) + parseInt(b);
            }, 0);

            let canvas = `<canvas height="200" width="200" id="${cId}"></canvas>`;

            if(totalUsage == 0){
                canvas = '<div style="min-height: 200;" class="text-center "><i class="fas fa-info-circle  text-primary me-2"></i>No Usage</div>'
            }


            let x = $(`<div class='col-md-3'>
                  <div class="card bg-dark text-white">
                      <div class="card-body">
                        <h4 class="mb-3 text-center"><i class="${config.icon} me-2"></i>${title}</h4>
                        ${canvas}
                      </div>
                  </div>
              </div>`);

            if(totalUsage > 0){
                let graphDataSets = [
                    {
                        label: "total",
                        borderColor: 'rgba(46, 204, 113, 1)',
                        pointBackgroundColor: "rgba(46, 204, 113, 1)",
                        pointBorderColor: "rgba(46, 204, 113, 1)",
                        data: values
                    }
                ];

                let options = {responsive: true};

                if (config.formatBytes) {
                      options.scales = scalesBytesCallbacks;
                      options.tooltips = toolTipsBytesCallbacks
                }else{
                    options.scales = {
                        yAxes: [{
                          ticks: {
                            precision: 0
                          }
                      }],
                    }
                }

                options.legend = {
                    display: false
                 },

                 options.elements = {
                    point:{
                        radius: 0
                    }
                }

                 // scales.yAxes.ticks
                options.scales.yAxes[0].gridLines = {
                    color: "rgba(0, 0, 0, 0)",
                }
                options.scales.yAxes[0].ticks.beginAtZero = false;
                options.scales.xAxes = [{
                    gridLines: {
                         color: "rgba(0, 0, 0, 0)",
                     },
                     ticks: {
                        callback: function(){
                            return '';
                        }
                      }
                }]
                new Chart(x.find("#" + cId), {
                  type: 'line',
                  data: {
                      datasets: graphDataSets,
                      labels: labels
                  },
                  options: options
                });
            }
            y[0].append(x[0]);
        });
        $("#totalsGraphs").empty().append(y)
    });
});

$("#overviewBox").on("keyup", "#filterDashProjectAnalyticsProject", function(e){
    let ul = $("#overviewGraphs");
    let search = $(this).val().toLowerCase();
    ul.find(".projectRow").filter(function(){
        $(this).toggle($(this).data("project").toLowerCase().indexOf(search) > -1)
    });
});


function loadUserDashboardGraphs()
{
    $("#userDashboardGraphs").empty();
    let x = {dashboardId: currentDashboard};

    if(dashboardRefreshInterval != null){
        clearInterval(dashboardRefreshInterval);
    }

    ajaxRequest(globalUrls.user.dashboard.get, x, (data)=>{
        data = makeToastr(data);
        $("#dashboardTitle").text(" Last Refresh: " + moment().format("llll"));
        dashboardRefreshInterval = setInterval(loadUserDashboardGraphs, 90000);

        $.each(data.graphsData, (i, graph)=>{
            let x = $(`<div class="card bg-dark text-white" id="${graph.graphId}">
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
            let scales = graph.formatBytes ? scalesBytesCallbacks : {yAxes: [{}]}
            scales.yAxes[0].gridLines = {drawBorder: false}
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
                        data: graph.data,
                        pointRadius: 0,
                        formatBytes: graph.formatBytes,
                        lineTension: 0,
                        borderWidth: 2
                    }]
                },
                options: {
                    animation: {
                        duration: 0
                    },
                    scales:scales,
                    tooltips: {
                        intersect: false,
                        mode: 'index',
                        callbacks: {
                            label: function(tooltipItem, myData) {
                                let ds = myData.datasets[tooltipItem.datasetIndex];
                                var label = ds.label || '';

                                if (label) {
                                    label += ': ';
                                }
                                if(ds.hasOwnProperty("formatBytes") && ds.formatBytes){
                                    label += formatBytes(tooltipItem.value);
                                }else{
                                    label += parseFloat(tooltipItem.value).toFixed(2);
                                }

                                return label;
                            }
                        }
                    }
                }
            });
            $("#userDashboardGraphs").append(x);
        });
    });
}

$(document).on("click", ".viewDashboard", function(){
    $("#userDashboardsList").find(".active").removeClass("active");
    $(this).addClass("active");
    $("#editDashboardGraphs").removeClass("btn-primary").addClass("btn-outline-primary");

    let dashId = $(this).attr("id");
    if(dashId == "generalDashboardLink"){
        if(dashboardRefreshInterval != null){
            clearInterval(dashboardRefreshInterval);
        }

        $("#generalDashboard").show();
        $("#projectAnalyticsDashboard").hide();
        $("#userDashboard").hide();
        $("#userDashboardGraphs").empty();
        currentDashboard = null;
        return false;
    }else if(dashId == "projectAnalyticsDashboardLink"){
        $("#projectAnalyticsDashboard").show();
        $("#generalDashboard").hide();
        $("#userDashboard").hide();
        $("#userDashboardGraphs").empty();
        currentDashboard = null;
        return false;
    }

    currentDashboard = dashId;
    $("#projectAnalyticsDashboard").hide();
    $("#generalDashboard").hide();
    $("#userDashboard").show();
    loadUserDashboardGraphs()
});

$(document).on("click", "#newDashboardBtn", function(){
    $.confirm({
        title: "Create a Dashboard",
        content: `<div class="mb-2">
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
            <div class="mb-2">
                <label>Name</label>
                <input class="form-control" name="name" />
            </div>
            <div class="mb-2">
                <label>Instance</label>
                <select id="addDashMetricInstanceSelect" class="form-select">
                </select>
            </div>
            <div class="mb-2">
                <label>Metric</label>
                <select id="addDashMetricMetricSelect" class="form-select disabled" disabled>
                    <option value=''>Select Instance</option>
                </select>
            </div>
            <div class="mb-2">
                <label>Filter</label>
                <select id="addDashMetricFilterSelect" class="form-select disabled" disabled>
                    <option value=''>Select Metric</option>
                </select>
            </div>
            <div class="mb-2">
                <label>Range</label>
                <select class="form-select float-end" id="addDashMetricRangeSelect" disabled>
                    <option value="">Please Select</option>
                    <option value="-15 minutes">Last 15 Minutes</option>
                    <option value="-30 minutes">Last 30 Minutes</option>
                    <option value="-45 minutes">Last 45 Minutes</option>
                    <option value="-60 minutes">Last 60 Minutes</option>
                    <option value="-3 hours">Last 3 Hours</option>
                    <option value="-6 hours">Last 6 Hours</option>
                    <option value="-12 hours">Last 12 Hours</option>
                    <option value="-24 hours">Last 24 Hours</option>
                    <option value="-2 days">Last 2 Days</option>
                    <option value="-3 days">Last 3 Days</option>
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
                    let rangeInput = $("#addDashMetricRangeSelect");
                    let name = nameInput.val().trim();
                    let instance = instanceInput.val().trim();
                    let metric = metricInput.val().trim();
                    let filter = filterInput.val().trim();
                    let range = rangeInput.val().trim();

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
                    }else if(range == ""){
                        filterInput.focus();
                        $.alert("Please select range");
                        return false;
                    }

                    let x = {
                        dashboardId: currentDashboard,
                        name: name,
                        hostId: tempObjs[$("#addDashMetricInstanceSelect").find(":selected").data()["host"]].hostId,
                        instance: instance,
                        metricId: metric,
                        filter: filter,
                        range: range
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
                     options += `<optgroup label="${host}">`
                    $.each(details.instances, (instance, _)=>{
                        options += `<option data-host="${host}" value='${instance}'>${instance}</option>`;
                    });
                    options += `</optgroup>`
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
        $("#addDashMetricRangeSelect").attr("disabled", true);
        $("#addDashMetricFilterSelect").empty().append(`<option value=''>Select Metric</option>`).attr("disabled", true);
        $("#metricGraphBody").empty();
        return false;
    }
    $("#addDashMetricRangeSelect").attr("disabled", false);
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
</script>

<?php
    require __DIR__ . "/../modals/hosts/editDetails.php";
?>
