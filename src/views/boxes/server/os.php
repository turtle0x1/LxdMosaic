<div class="serverViewBox" id="serverOSView">
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-dark card-body">
                <h5>System</h5>
                <table class="table table-sm table-dark table-bordered" id="systemEndpointsTbl">
                    <thead></thead>
                    <tbody></tbody>
                </table>
                <h5>Applications</h5>
                <table class="table table-sm table-dark table-bordered" id="systemApplicationsTbl">
                    <thead></thead>
                    <tbody></tbody>
                </table>
                <h5>Services</h5>
                <table class="table table-sm table-dark table-bordered" id="systemServicesTbl">
                    <thead></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="col-md-9">
            <?php require __DIR__ . "/os/apps.php" ?>
            <?php require __DIR__ . "/os/services.php" ?>
            <?php require __DIR__ . "/os/system.php" ?>
        </div>
    </div>
</div>
<script>
    function loadHostOSSidebar(targetUrl = null) {
        ajaxRequest('/api/hosts/os', {
            hostId: currentServer.hostId
        }, (data) => {
            data = makeToastr(data)

            function _makeTrs(table, entries, entryClass) {
                let trs = ""
                entries.forEach(endpoint => {
                    const url = `host/${currentServer.hostAlias}/os/${entryClass}/${endpoint}`
                    trs += `<tr>
                        <td class="table-${targetUrl == url ? "primary" : ""}"><a class="text-${targetUrl == url ? "dark" : ""}" href="/${url}" data-navigo>${endpoint}</a></td>
                    </tr>`
                })
                $(table).empty().append(trs)
            }
            _makeTrs("#systemEndpointsTbl > tbody", data.systemEndpoints, "system")
            _makeTrs("#systemApplicationsTbl > tbody", data.applications, "apps")
            _makeTrs("#systemServicesTbl > tbody", data.services, "services")
            router.updatePageLinks()
        });
    }

    function loadHostOS(req) {
        currentContainerDetails = null;
        let hostId = req.data.hostId;
        currentServer.hostId = hostId
        currentServer.hostAlias = hostsAliasesLookupTable[hostId]
        createDashboardSidebar()
        _loadServerDetailsIfReq(hostId);
        changeActiveNav(".overview")
        $(".boxSlide, .serverViewBox, .serverOSBox").hide();
        $("#serverOSView, #serverBox").show();

        addBreadcrumbs(["Dashboard", hostsAliasesLookupTable[hostId], "OS"], ["", "", "active"], false, ["/", "", ""]);

        $("#serverBoxNav").find(".active").removeClass("active")
        $("#serverBoxNav .nav-item[data-view='serverOSView'] > .nav-link").addClass("active")

        loadHostOSSidebar()
    }
</script>