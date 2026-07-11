<div id="settingsBox" class="boxSlide">
    <?php foreach (['instanceSettings', 'recordedActions', 'instanceHosts', 'usersList', 'retiredData', 'userProjectOverview', 'instanceTypesOverview', 'softwareAssets', 'timers', 'imageServers'] as $component) {
        require_once __DIR__ . '/boxComponents/settings/' . $component . '.html';
    } ?>
</div>

<script>
    // Bad this is a global but saves 2 extra api's
    var allInstanceTypes = {};

    var currentProvider;

    var adminSettingUrls = {
        settings: '/admin/settings',
        hosts: '/admin/hosts',
        imageServers: '/admin/imageServers',
        instanceTypes: '/admin/instanceTypes',
        users: '/admin/users',
        userAccessControl: '/admin/userAccessControl',
        history: '/admin/history',
        retiredData: '/admin/retiredData',
        softwareSnapshots: '/admin/softwareAssets',
        timersSnapshots: '/admin/timers',
    };

    function putAdminSidebar(selected) {
        let sidebar = '';
        if (!userDetails.isAdmin) {
            $("#saveSettings, #saveLdapSettings, #addUser, #recordedActionsCard, #usersCard").remove();
        } else {
            sidebar += `
        <li class="c-sidebar-nav-title text-success pt-2"><u>LXDMosaic</u></li>
        <li class="nav-item mt-2 instance-settings">
            <a class="nav-link p-0 ${selected === `${adminSettingUrls.settings}` ? "active" : null }" href="${adminSettingUrls.settings}" data-navigo>
                <i class="fas fa-sliders-h me-2"></i>Settings
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0 ${selected === `${adminSettingUrls.hosts}` ? "active" : null } " href="${adminSettingUrls.hosts}" data-navigo>
                <i class="fas fa-server me-2"></i>Hosts
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0 ${selected === `${adminSettingUrls.instanceTypes}` ? "active" : null }" href="${adminSettingUrls.instanceTypes}" data-navigo>
                <i class="fas fa-cloud me-2"></i>Instance Types
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0 ${selected === `${adminSettingUrls.imageServers}` ? "active" : null }" href="${adminSettingUrls.imageServers}" data-navigo>
                <i class="fas fa-images me-2"></i>Image Servers
            </a>
        </li>
        <li class="c-sidebar-nav-title text-success pt-2"><u>Users</u></li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0  ${selected === `${adminSettingUrls.users}` ? "active" : null }" href="${adminSettingUrls.users}" data-navigo>
                <i class="fas fa-users-cog me-2"></i>All
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0  ${selected === `${adminSettingUrls.userAccessControl}` ? "active" : null }" href="${adminSettingUrls.userAccessControl}" data-navigo>
                <i class="fas fa-users-cog me-2"></i>Project Access
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0  ${selected === `${adminSettingUrls.history}` ? "active" : null }" href="${adminSettingUrls.history}" data-navigo>
                <i class="fas fa-history me-2"></i>Audit Log
            </a>
        </li>
        <li class="c-sidebar-nav-title text-success pt-2"><u>Data</u></li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0  ${selected === adminSettingUrls.softwareSnapshots ? "active" : null }" href="${adminSettingUrls.softwareSnapshots}" data-navigo>
                <i class="fas fa-list me-2"></i>Software Snapshots
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0  ${selected === adminSettingUrls.timersSnapshots ? "active" : null }" href="${adminSettingUrls.timersSnapshots}" data-navigo>
                <i class="fas fa-hourglass-half me-2"></i>Timers Snapshots
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link p-0  ${selected === `${adminSettingUrls.retiredData}` ? "active" : null }" href="${adminSettingUrls.retiredData}" data-navigo>
                <i class="fas fa-eraser me-2"></i>Retired Data
            </a>
        </li>
        `
        }

        $("#sidebar-ul").empty().append(sidebar);
        router.updatePageLinks()
    }

    function loadSettingsView() {
        $(".boxSlide").hide();
        $(".settingsBox").hide();
        setBreadcrumb("Admin Settings", "active", "/admin");
        changeActiveNav(null)
        $(".viewSettings").addClass("active");
        $("#settingsBox").show();
        putAdminSidebar(adminSettingUrls.settings);
        changeActiveNav(null)
        $(".viewSettings").addClass("active");
        $("#instanceSettingsBox").show();
        $(".sidebar-fixed").addClass("sidebar-lg-show");
        allInstanceTypes = {};
        currentProvider = null;

        let hosts = '';

        loadInstanceSettings();
        $(".settingsBox").hide();
        $("#instanceSettingsBox").show();
        addBreadcrumbs(["LXDMosaic Settings"], ["active"], true);

    }
</script>

<?php
require __DIR__ . '/../modals/users/projectAccess.php';
?>