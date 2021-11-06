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


var unknownServerDetails = {
    cpu: {
        sockets: [{vendor: "Unknown Vendor"}],
        total: "Unknown Cpu Total"
    },
    memory: {
        used: "Uknown Memory Use",
        total: "Uknown Memory Total"
    }
};

function nextLetter(s){
    return s.replace(/([a-zA-Z])[^a-zA-Z]*$/, function(a){
        var c= a.charCodeAt(0);
        switch(c){
            case 90: return 'A';
            case 122: return 'a';
            default: return String.fromCharCode(++c);
        }
    });
}


function createDashboardSidebar()
{
    if($("#sidebar-ul").find(".view-container").length == 0){
        ajaxRequest(globalUrls.universe.getEntities, {}, (data)=>{
            data = makeToastr(data);

            let currentHostId = currentServer.hostId == null ? null : currentServer.hostId;

            let hosts = `<li class="nav-item mt-2">
                <a class="nav-link ${currentHostId == null && currentContainerDetails == null ? "active" : ""} p-0" href="/" data-navigo>
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>`;

            $.each(data.clusters, function(i, item){
                hosts += `<li data-cluster="${i}" class="c-sidebar-nav-title cluster-title text-success ps-1 pt-2"><u>Cluster ${i}</u></li>`;

                hostsTrs += `<tr><td colspan="999" class="bg-success text-center text-white">Cluster ${i}</td></tr>`

                $.each(item.members, function(_, host){
                    let disabled = "";
                    let expandBtn = '<button class="btn btn-outline-secondary float-end showServerInstances"><i class="fas fa-caret-left"></i></button>';
                    let active = currentHostId == host.hostId ? "active" : ""

                    if(!host.hostOnline){
                        disabled = "disabled text-warning text-strikethrough";
                        expandBtn = '';
                    }

                    hosts += `<li data-hostId="${host.hostId}" data-alias="${host.alias}" class="nav-item containerList dropdown">
                        <a class="nav-link ${active} ${disabled}" href="/host/${host.hostId}/overview" data-navigo>
                            <i class="fas fa-server"></i> ${host.alias}
                            ${expandBtn}
                        </a>
                        <div id="${host.hostId}" class="collapse">
                            <ul class="dropdown-menu dropdown-menu-dark hostInstancesUl">
                            </ul>
                        </div>
                    </li>`;
                });
            });

            hosts += `<li class="c-sidebar-nav-title text-success pt-2"><u>Standalone Hosts</u></li>`;

            $.each(data.standalone.members, function(_, host){
                let disabled = "";
                let expandBtn = `<button class="btn btn-outline-secondary btn-toggle collapsed float-end showServerInstances" ata-bs-toggle="collapse" data-bs-target="#${host.hostId}" aria-expanded="false"><i class="fas fa-caret-left"></i></button>`;
                let active = currentHostId == host.hostId ? "active" : ""

                if(host.hostOnline == false){
                    disabled = "disabled text-warning text-strikethrough";
                    expandBtn = '';
                }

                hosts += `<li class="mb-2" data-hostId="${host.hostId}" data-alias="${host.alias}">
                    <div>
                        <a class="nav-link ${active} d-inline ps-0 ${disabled}" href="/host/${host.hostId}/overview" data-navigo>
                            <i class="fas fa-server"></i> ${host.alias}
                        </a>
                        <button class="btn  btn-outline-secondary d-inline btn-sm btn-toggle align-items-center rounded collapsed showServerInstances d-inline float-end me-2" data-bs-toggle="collapse" data-bs-target="#host-${host.hostId}" aria-expanded="false">
                            <i class="fas fa-caret-left"></i>
                        </button>
                    </div>
                    <div class="collapse mt-2 bg-dark text-white" id="host-${host.hostId}">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 hostInstancesUl" style="display: inline;">
                        </ul>
                    </div>
                 </li>`
            });


            $("#sidebar-ul").empty().append(hosts);
            router.updatePageLinks()
        });
    }else {
        $("#sidebar-ul").find(".active").removeClass("active");
        if(currentContainerDetails !== null && $.isNumeric(currentContainerDetails.hostId)){
            $("#sidebar-ul").find(`.nav-link[href="/instance/${hostIdOrAliasForUrl(currentContainerDetails.alias ,currentContainerDetails.hostId)}/${currentContainerDetails.container}"]`).addClass("active")
        }else if(currentServer !== null && $.isNumeric(currentServer.hostId)){
            $("#sidebar-ul").find(`.nav-link[href="/host/${currentServer.hostId}/overview"]`).addClass("active")
        }else{
            $("#sidebar-ul").find(".nav-link:eq(0)").addClass("active")
        }
    }
}

$(document).on("click", '.dashSidebarFilter', function(e) {
   e.preventDefault();
   var target = $(e.target)
   let targetData = target.data();
   if(!targetData.hasOwnProperty("search")){
       targetData = target.parent().data();
   }

   let search = "";
   if (targetData.search == "containers"){
       search = "container";
   } else if (targetData.search == "vms"){
       search = "vm";
   }

   $(this).parents("ul").find('.search_concept').html(`<i class="fas fa-${targetData.icon}"></i>`).data("filter", search)

   let ul = target.parents("ul");
   ul.parents("li").find(".hostInstancesUl").css("min-height", "200px");
   let inputSearch = ul.find(".filterHostsInstances").val().toLowerCase();
   ul.find(".view-container").filter(function(){
       $(this).toggle($(this).data("type").toLowerCase().indexOf(search) > -1 && $(this).text().toLowerCase().indexOf(inputSearch) > -1)
   });
});

function addHostContainerList(hostId, hostAlias) {
    ajaxRequest(globalUrls.hosts.instances.getHostContainers, {hostId: hostId}, (data)=>{
        data = makeToastr(data);
        let containers = "";
        if(Object.keys(data).length > 0){

            if(Object.keys(data).length > 5){
                containers += `<li class="">
                    <div class="input-group pe-3 mb-2" style="padding-left: 5px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="search_concept" data-filter=""><i class="fas fa-filter"></i></span> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" role="menu">
                          <li class='dropdown-item dashSidebarFilter' data-search="all" data-icon="filter"><i class="fas fa-filter me-2"></i>All</li>
                          <li class='dropdown-item dashSidebarFilter' data-search="containers" data-icon="box"><i class="fas fa-box me-2"></i>Containers</li>
                          <li class='dropdown-item dashSidebarFilter' data-search="vms" data-icon="vr-cardboard"><i class="fas fa-vr-cardboard me-2"></i>Virtual Machines</li>
                        </ul>
                        <input type="text" class="form-control form-control-sm bg-dark text-white filterHostsInstances" placeholder="Search instances...">
                    </div>
                </li>`
            }

            $.each(data, function(containerName, details){
                let active = "";
                if(currentContainerDetails !== null && currentContainerDetails.hasOwnProperty("container")){
                    if(hostId == currentContainerDetails.hostId && containerName == currentContainerDetails.container){
                        active = "text-info"
                    }
                }

                let typeFa = "box";
                let type = "container";

                if(details.hasOwnProperty("type") && details.type == "virtual-machine"){
                    typeFa = "vr-cardboard";
                    type = "vm";
                }

                let osIconMap = {
                    centos: 'centos',
                    opensuse: 'suse',
                    fedora: 'fedora',
                    ubuntu: 'ubuntu'
                };

                let osIcon = "linux";
                let instanceImage = ""

                if(details.config.hasOwnProperty("image.os") && details.config["image.os"] != null){
                    instanceImage = details.config["image.os"].toLowerCase();
                }

                if(osIconMap.hasOwnProperty(instanceImage)){
                    osIcon = osIconMap[instanceImage];
                }

                containers += `<li class="view-container"
                    data-host-id="${hostId}"
                    data-container="${containerName}"
                    data-alias="${hostAlias}"
                    data-type="${type}">
                  <a class="nav-link p-0 m-0 ${active}" href="/instance/${hostIdOrAliasForUrl(hostAlias, hostId)}/${containerName}" data-navigo>
                    <i style="min-width: 20px" class="nav-icon me-1 ${statusCodeIconMap[details.state.status_code]}"></i>
                    <i style="min-width: 20px" class="nav-icon me-1 fas fa-${typeFa}"></i>
                    <i style="min-width: 20px" class="nav-icon me-1 fab fa-${osIcon}"></i>
                    <span class="text-truncate" style="max-width: 150px;">
                        ${containerName}
                    </span>
                  </a>
                </li>`;
            });
        }else {
            containers += `<li class="nav-item text-center text-warning">No Instances</li>`;
        }
        $("#sidebar-ul").find("li[data-hostid=" + hostId + "] ul").empty().append(containers).show()
        router.updatePageLinks()
    });
}

$(document).on("keyup", ".filterHostsInstances", function(e){
    let hostUl = $(this).parents("ul");
    let search = $(this).val().toLowerCase();
    let typeFilter = hostUl.find(".search_concept").data("filter");
    let hostInstancesUl = hostUl.parents("li").find(".hostInstancesUl");
    hostInstancesUl.css("min-height", "200px");
    hostInstancesUl.find(".view-container").filter(function(){
        $(this).toggle($(this).text().toLowerCase().indexOf(search) > -1 && $(this).data("type").toLowerCase().indexOf(typeFilter) > -1)
    });
});

$(document).on("click", ".showServerInstances", function(e){
    e.preventDefault();

    let parentLi = $(this).parents("li");

    if(parentLi.hasClass("open")){
        parentLi.find("ul").empty();
        parentLi.removeClass("open");
        parentLi.find(".hostInstancesUl").css("min-height", "0px");
        $(this).html('<i class="fas fa-caret-left"></i>')
        return false;
    }else{
        $(this).html('<i class="fas fa-caret-down"></i>')
        parentLi.addClass("open");
    }

    let hostId = parentLi.data("hostid");
    let hostAlias = parentLi.data("alias");

    currentContainerDetails = null;

    addHostContainerList(hostId, hostAlias);

    return false;
});

function loadDashboard(){
    currentContainerDetails = null
    currentServer = {hostId: null}
    if(recordActionsEnabled == 0){
        $("#goToEvents").hide();
    }
    $('[data-toggle="tooltip"]').tooltip({html: true})
    createDashboardSidebar();
    window.setInterval(clearOldOperations, 60 * 1000);
    $.contextMenu({
            selector: '.view-container',
            items: {
                "snapshot": {
                    name: "Snapshot",
                    icon: "fas fa-camera",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        snapshotContainerConfirm(item.data("hostId"), item.data("container"));
                    }
                },
                "copy": {
                    name: "Copy",
                    icon: "copy",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        copyContainerConfirm(item.data("hostId"), item.data("container"));
                    }
                },
                "edit": {
                    name: "Rename",
                    icon: "edit",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        renameContainerConfirm(item.data("hostId"), item.data("container"), false, item.data("alias"));
                    }
                },
                "delete": {
                    name: "Delete",
                    icon: "delete",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        deleteContainerConfirm(item.data("hostId"), item.data("alias"), item.data("container"));
                    }
                },
                "backup": {
                    name: "backup",
                    icon: "fas fa-save",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        backupContainerConfirm(item.data("hostId"), item.data("alias"), item.data("container"), null, false);
                    }
                },
            }
        });

        $.contextMenu({
            selector: '.filesystemObject',
            items: {
                "delete": {
                    name: "Delete",
                    icon: "delete",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        deleteFilesystemObjectConfirm(item.data("path"));
                    }
                }
            }
        });
        $.contextMenu({
            selector: '#filesystemTable',
            items: {
                "upload": {
                    name: "Upload",
                    icon: "upload",
                    callback: function(key, opt, e){
                        let item = opt["$trigger"];
                        $("#modal-container-files-upload").modal("show");
                    }
                }
            }
        });

    if(dashboardRefreshInterval != null){
        clearInterval(dashboardRefreshInterval);
    }

    $("#userDashboardGraphs").empty();
    $(".boxSlide, #userDashboard, #projectAnalyticsDashboard").hide();
    $("#overviewBox, #generalDashboard").show();
    setBreadcrumb("Dashboard", "active", "/");
    changeActiveNav(".overview")

    ajaxRequest(globalUrls.dashboard.get, {}, (data)=>{
        data = makeToastr(data);

        let lis = `<li class="nav-item">
          <div class="nav-link active viewDashboard" id="generalDashboardLink" href="#"><i class="fas fa-tachometer-alt me-2"></i>General</div>

        </li>
        <li class="nav-item">
            <div class="nav-link viewDashboard" id="projectAnalyticsDashboardLink" href="#"><i class="fas fa-chart-bar me-2"></i>Project Analytics</div>
        </li>`;

        $.each(data.userDashboards, (_, dashboard)=>{
            lis += `<li class="nav-item">
              <div class="nav-link viewDashboard" id="${dashboard.id}" href="#"><i class="fas fa-house-user me-2"></i>${dashboard.name}</div>
            </li>`;
        });

        $("#userDashboardsList").empty().append(lis);

        let projectsDropdown = "";

        $.each(data.clustersAndHosts.clusters, function(i, item){

            let cTitleClass = userDetails.isAdmin ? "cluster-title" : "cluster-title-not-admin";

            projectsDropdown += `<b>Cluster ${i}</b>`

            $.each(item.members, function(_, host){
                let disabled = "";
                if(!host.hostOnline){
                    disabled = "disabled text-warning text-strikethrough";
                }

                let projects = "<div class='text-center text-info'><i class='fas fa-info-circle me-2'></i>Not Supported</div>";

                if(host.resources.hasOwnProperty("extensions") && host.resources.extensions.supportsProjects){
                    projects = "<select class='form-control changeHostProject'>";
                    $.each(host.projects, function(o, project){
                        let selected = project == host.currentProject ? "selected" : "";
                            projects += `<option data-alias="${host.alias}" data-host='${data.hostId}'
                                value='${project}' ${selected}>
                                ${project}</option>`;
                    });
                    projects += "</select>";
                }

                if(host.hostOnline == true){
                    projectsDropdown += `<div><b>${host.alias}</b>${projects}</div>`
                }
            });
        });

        projectsDropdown += `<b class="text-success">Standalone Hosts</b>`

        $.each(data.clustersAndHosts.standalone.members, function(_, host){
            let disabled = "";

            if(host.hostOnline == false){
                disabled = "disabled text-warning text-strikethrough";
            }

            let projects = "<div class='text-center text-info'><i class='fas fa-info-circle me-2'></i>Not Supported</div>";

            if(host.resources.hasOwnProperty("extensions") && host.resources.extensions.supportsProjects){
                projects = "<select class='form-control changeHostProject'>";
                $.each(host.projects, function(o, project){
                    let selected = project == host.currentProject ? "selected" : "";
                        projects += `<option data-alias="${host.alias}" data-host='${host.hostId}'
                            value='${project}' ${selected}>
                            ${project}</option>`;
                });
                projects += "</select>";
            }

            if(host.hostOnline == true){
                projectsDropdown += `<div><i class="fas fa-server me-2"></i><b>${host.alias}</b>${projects}</div>`;
                openHostOperationSocket(host.hostId, host.currentProject);
            }
        });
        $("#navProjectControlHostList").empty().append(projectsDropdown);


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

        $("#overviewGraphs").empty();
        $("#totalsGraphs").empty();

        $.each(data.projectGraphData.projectAnalytics, (alias, projects)=>{
            $.each(projects, (project, analytics)=>{
                let y = $(`
                <div class="row projectRow" data-project="${project}">
                    <div class="col-md-12 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                        <h4 class="mb-2"><i class="fas fa-server text-info me-2"></i>${alias}</h4>
                        <h4><i class="fas fa-project-diagram text-info me-2"></i>${project}</h4>
                    </div>
                    <div class="row graphs">
                    </div>
                </div>
                `);

                $.each(displayItems, (title, config)=>{
                    let labels = [];
                    let values = [];
                    let limits = [];

                    let cId = project + "-" + title.toLowerCase();

                    $.each(data.projectGraphData.projectAnalytics[alias][project][title], (_, entry)=>{
                        labels.push(moment.utc(entry.created).local().format("HH:mm"))
                        values.push(entry.value)
                        limits.push(entry.limit)
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
                              <div class="card-header">
                                  <i class="${config.icon} me-2"></i>${title}
                              </div>
                              <div class="card-body">
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

                        let filtLimits = limits.filter(onlyUnique)
                        //
                        if(filtLimits.length !== 1 || filtLimits[0] !== null){
                            graphDataSets.push({
                                label: "limit",
                                borderColor: '#09F',
                                pointBackgroundColor: "#09F",
                                pointBorderColor: "#09F",
                                data: limits
                            })
                        }

                        let options = {responsive: true};

                        if (config.formatBytes) {
                              options.scales = scalesBytesCallbacks;
                              options.tooltips = toolTipsBytesCallbacks
                        }else{
                            options.scales = {
                                yAxes: [{
                                  ticks: {
                                    precision: 0,
                                    beginAtZero: true
                                  }
                                }]
                            }
                        }

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

                $("#overviewGraphs").append(y)
            });
        });

        let y = $(`<div class="row mb-2" ></div>`)
        $.each(displayItems, (title, config)=>{


            let labels = [];
            let values = [];
            let limits = [];

            let cId = title.toLowerCase();

            $.each(data.projectGraphData.totals[title], (created, value)=>{
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
        $("#totalsGraphs").append(y)
    });
}

$(document).on("click", ".cluster-title", function(e){
    if(!userDetails.isAdmin){
        return false;
    }
    let x = $(this).data();
    $("#sidebar-ul").find(".text-info").removeClass("text-info");
    $("#sidebar-ul").find(".active").removeClass("active");
    $(this).addClass("text-info");
    loadClusterView(x.cluster);
});

$(document).on("click", "#openSearch", function(){
    $.confirm({
        title: `Search`,
        content: `
            <div class="mb-2">
                <label>IP Address IPV4/IPV6</label>
                <input class="form-control" name="ip" />
            </div>
        `,
        buttons: {
            cancel: {
                btnClass: "btn btn-secondary",
                text: "cancel"
            },
            search: {
                btnClass: "btn btn-success",
                text: "Search",
                action: function(){
                    let x = {
                        ip: this.$content.find("input[name=ip]").val()
                    }

                    ajaxRequest(globalUrls.networks.tools.findIpAddress, x, (data)=>{
                        data = makeToastr(data);
                        if(data.state == "error"){
                            return false;
                        }
                        if(data.result == false){
                            makeToastr({state: "error", message: "Couldn't find instance"})
                            return false;
                        }
                        router.navigate(`/instance/${hostIdOrAliasForUrl(data.result.alias, data.result.hostId)}/${data.result.container}`);
                    });
                }
            }
        }
    });
});

</script>

<?php
    require __DIR__ . "/../modals/hosts/editDetails.php";
?>
