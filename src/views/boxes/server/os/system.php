<div class="serverOSBox" id="serverOSSystem">
    <div class="row">
        <div class="col-md-12 border-bottom mb-2">
            <h5 class="mb-2" id="systemEndpointName"></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            foreach (glob(__DIR__ . '/system/*.html') as $file) {
                require $file;
            }
            ?>
        </div>
    </div>
</div>
<script>
    function loadHostOSSystem(req) {
        currentContainerDetails = null;
        let hostId = req.data.hostId;
        currentServer.hostId = hostId
        currentServer.hostAlias = hostsAliasesLookupTable[hostId]
        createDashboardSidebar()
        _loadServerDetailsIfReq(hostId);
        changeActiveNav(".overview")
        $(".boxSlide, .serverViewBox, .serverOSBox, .systemEndpointBox").hide();
        $("#serverOSView, #serverBox, #serverOSSystem").show();
        addBreadcrumbs(["Dashboard", hostsAliasesLookupTable[hostId], "OS", "System", req.data.endpoint], ["", "", "", "", "active"], false, ["/", "", ""]);

        $("#serverBoxNav").find(".active").removeClass("active")
        $("#serverBoxNav .nav-item[data-view='serverOSView'] > .nav-link").addClass("active")

        loadHostOSSidebar(req.url)

        $("#systemEndpointName").text(req.data.endpoint)
        ajaxRequest('/api/hosts/os/system/endpoint', {
            hostId: req.data.hostId,
            endpoint: req.data.endpoint
        }, (data) => {
            data = makeToastr(data)
            $(".systemEndpointBox").hide()
            if (req.data.endpoint == "logging") {
                renderLogging(data)
            } else if (req.data.endpoint == "storage") {
                renderStorage(data)
            } else if (req.data.endpoint == "network") {
                routeNetwork(data)
            } else if (req.data.endpoint == "provider") {
                renderProvider(data)
            } else if (req.data.endpoint == "security") {
                renderSecurity(data)
            } else if (req.data.endpoint == "update") {
                renderUpdate(data)
            } else {
                renderNotImplemented(data)
            }
        })
    }
</script>