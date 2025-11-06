<div id="overviewBox" class="boxSlide">
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom">
                <ul class="nav nav-tabs w-100" id="userDashboardsList" style="border: none !important;">
                    <li class="nav-item">
                      <div class="nav-link active viewDashboard" id="generalDashboardLink" href="#"><i class="fas fa-tachometer-alt me-2"></i>General</div>

                    </li>
                    <li class="nav-item">
                        <div class="nav-link viewDashboard" id="projectAnalyticsDashboardLink" href="#"><i class="fas fa-chart-bar me-2"></i>Project Analytics</div>
                    </li>
                </ul>
                <button class="btn btn-outline-primary p-1" id="newDashboardBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create dashboard">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>

    <?php
        require __DIR__ . '/boxComponents/dashboard/generalDashboard.html';
    require __DIR__ . '/boxComponents/dashboard/userDashboard.html';
    require __DIR__ . '/boxComponents/dashboard/projectAnalytics.html';
    ?>

</div>

<script>
var currentDashboard = null
var dashboardRefreshInterval = null;

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
        $("#analyticsHistoryDuration option:eq(0)").prop("selected", true)
        $("#analyticsHistoryDuration").trigger("change")
        currentDashboard = null;
        return false;
    }

    currentDashboard = dashId;
    $("#projectAnalyticsDashboard").hide();
    $("#generalDashboard").hide();
    $("#userDashboard").show();
    loadUserDashboardGraphs()
});

function _makeDashboardSidebarHostItem(host, currentHostId) {
    let disabled = "";
    let expandBtn = `<button class="btn  btn-outline-secondary d-inline btn-sm btn-toggle align-items-center rounded collapsed showServerInstances d-inline float-end me-2 toggleDropdown" data-bs-toggle="collapse" data-bs-target="#host-${host.hostId}" aria-expanded="false"><i class="fas fa-caret-left"></i></button>`;
    let active = currentHostId == host.hostId ? "active" : ""

    if(host.hostOnline != 1){
        disabled = "disabled text-warning text-strikethrough";
        expandBtn = '';
    }

    return `<li class="mb-2" data-hostId="${host.hostId}" data-alias="${host.alias}">
        <div>
            <a class="nav-link ${active} d-inline ps-0 ${disabled}" href="/host/${hostIdOrAliasForUrl(host.alias, host.hostId)}/overview" data-navigo>
                <i class="fas fa-server"></i> ${host.alias}
            </a>
            ${expandBtn}
        </div>
        <div class="collapse mt-2 bg-dark text-white" id="host-${host.hostId}">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 hostInstancesUl" style="display: inline;">
            </ul>
        </div>
     </li>`
}

function createDashboardSidebar()
{
    if($("#sidebar-ul").find("[href^='/host']").length == 0){
        ajaxRequest(globalUrls.universe.getEntities, {}, (data)=>{
            data = makeToastr(data);

            let currentHostId = currentServer.hostId == null ? null : currentServer.hostId;

            let hosts = `<li class="nav-item mt-2">
                <a class="nav-link ${currentHostId == null && currentContainerDetails == null && currentCluster.clusterId == null ? "active" : ""} p-0" href="/" data-navigo>
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>`;

            $.each(data.clusters, function(i, item){
                hosts += `<li href="/cluster/${i}" class="c-sidebar-nav-title cluster-title text-success pt-2" data-navigo><u>Cluster ${i}</u></li>`
                $.each(item.members, function(_, host){
                    hosts += _makeDashboardSidebarHostItem(host, currentHostId);
                });
            });

            hosts += `<li class="c-sidebar-nav-title text-success pt-2"><u>Standalone Hosts</u></li>`;

            $.each(data.standalone.members, function(_,host){
                hosts += _makeDashboardSidebarHostItem(host, currentHostId);
            });


            $("#sidebar-ul").empty().append(hosts);
            router.updatePageLinks()
        });
    }else {
        $("#sidebar-ul").find(".active").removeClass("active");
        if(currentContainerDetails !== null && $.isNumeric(currentContainerDetails.hostId)){
            $("#sidebar-ul").find(`.nav-link[href="/instance/${hostIdOrAliasForUrl(currentContainerDetails.alias ,currentContainerDetails.hostId)}/${currentContainerDetails.container}"]`).addClass("active")
        }else if(currentServer !== null && $.isNumeric(currentServer.hostId)){
            $("#sidebar-ul").find(`.nav-link[href="/host/${hostIdOrAliasForUrl(currentServer.hostAlias, currentServer.hostId)}/overview"]`).addClass("active")
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
                        active = "active"
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
                  <a class="nav-link ${active} text-truncate p-0 m-0 ${active}" href="/instance/${hostIdOrAliasForUrl(hostAlias, hostId)}/${containerName}" data-navigo>
                    <i style="min-width: 20px" class="nav-icon me-1 ${statusCodeIconMap[details.state.status_code]}"></i>
                    <i style="min-width: 20px" class="nav-icon me-1 fas fa-${typeFa}"></i>
                    <i style="min-width: 20px" class="nav-icon me-1 fab fa-${osIcon}"></i>
                    ${containerName}
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

    addHostContainerList(hostId, hostAlias);

    return false;
});

function loadDashboard(){
    currentContainerDetails = null
    currentServer = {hostId: null}
    $('[data-bs-toggle="tooltip"]').tooltip({html: true})
    createDashboardSidebar();
    window.setInterval(clearOldOperations, 60 * 1000);

    if(dashboardRefreshInterval != null){
        clearInterval(dashboardRefreshInterval);
    }

    $("#userDashboardGraphs").empty();
    $(".boxSlide, #userDashboard, #projectAnalyticsDashboard").hide();
    $("#overviewBox, #generalDashboard").show();
    setBreadcrumb("Dashboard", "active", "/");
    changeActiveNav(".overview")
    $("#userDashboardsList .userDashboard").remove();
    $("#userDashboardsList").find(".active").removeClass("active");
    $("#generalDashboardLink").addClass("active");

    $("#totalsGraphs").empty();
    $("#analyticsHistoryDuration option:eq(0)").prop("selected", true);
    $("#overviewHistoryDuration option:eq(0)").prop("selected", true);
    $("#filterDashProjectAnalyticsNoUsage").prop("checked", false);

    ajaxRequest(globalUrls.dashboard.get, {}, (data)=>{
        data = makeToastr(data);

        let dashboardTabs = '';

        $.each(data.userDashboards, (_, dashboard)=>{
            dashboardTabs += `<li class="nav-item">
              <div class="nav-link viewDashboard userDashboard" id="${dashboard.id}" href="#"><i class="fas fa-house-user me-2"></i>${dashboard.name}</div>
            </li>`;
        });

        $("#userDashboardsList").append(dashboardTabs);

        makeProjectOverviewGraphs(data.projectsUsageGraphData)
    });
}
</script>

<?php
    require __DIR__ . '/../modals/hosts/editDetails.php';
    ?>
