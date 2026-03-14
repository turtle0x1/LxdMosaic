<div class="serverOSBox" id="serverOSAppView">
    <div class="row">
        <h5 id="systemOSAppName"></h5>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            foreach (glob(__DIR__ . '/applications/*.html') as $file) {
                require $file;
            }
            ?>
        </div>
    </div>
</div>
<script>
    function loadHostOSApps(req) {
        currentContainerDetails = null;
        let hostId = req.data.hostId;
        currentServer.hostId = hostId
        currentServer.hostAlias = hostsAliasesLookupTable[hostId]
        createDashboardSidebar()
        _loadServerDetailsIfReq(hostId);
        changeActiveNav(".overview")
        $(".boxSlide, .serverViewBox,  .serverOSBox, .systemApplicationBox").hide();
        $("#serverOSView, #serverBox, #serverOSAppView").show();
        addBreadcrumbs(["Dashboard", hostsAliasesLookupTable[hostId], "OS", "Applications", req.data.app], ["", "", "", "", "active"], false, ["/", "", ""]);

        $("#serverBoxNav").find(".active").removeClass("active")
        $("#serverBoxNav .nav-item[data-view='serverOSView'] > .nav-link").addClass("active")

        loadHostOSSidebar(req.url)

        $("#systemOSAppName").text(req.data.app)

        ajaxRequest('/api/hosts/os/applications', {
            hostId,
            application: req.data.app
        }, (data) => {
            data = makeToastr(data)
            $(".systemEndpointBox").hide()
            if (req.data.app == "incus") {
                renderIncus(data)
            } else {
                renderNotImplemented(data)
            }
        })
    }
</script>