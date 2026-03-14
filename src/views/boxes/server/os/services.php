<div class="serverOSBox" id="serverOSServiceView">
    <div class="row">
        <h5 id="serverOSServiceName"></h5>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            foreach (glob(__DIR__ . '/services/*.html') as $file) {
                require $file;
            }
            ?>
        </div>
    </div>
</div>
<script>
    function loadHostOSServices(req) {
        currentContainerDetails = null;
        let hostId = req.data.hostId;
        currentServer.hostId = hostId
        currentServer.hostAlias = hostsAliasesLookupTable[hostId]
        createDashboardSidebar()
        _loadServerDetailsIfReq(hostId);
        changeActiveNav(".overview")
        $(".boxSlide, .serverViewBox,  .serverOSBox, .systemApplicationBox").hide();
        $("#serverOSView, #serverBox, #serverOSServiceView").show();
        addBreadcrumbs(["Dashboard", hostsAliasesLookupTable[hostId], "OS", "Services", req.data.service], ["", "", "", "", "active"], false, ["/", "", ""]);

        $("#serverBoxNav").find(".active").removeClass("active")
        $("#serverBoxNav .nav-item[data-view='serverOSView'] > .nav-link").addClass("active")

        loadHostOSSidebar(req.url)

        $("#serverOSServiceName").text(req.data.service)

        ajaxRequest('/api/hosts/os/services', {
            hostId,
            service: req.data.service
        }, (data) => {
            data = makeToastr(data)
            $(".systemServiceBox").hide()
            if (req.data.service == "iscsi") {
                renderIscsi(data)
            } else if (req.data.service == "lvm") {
                renderLvm(data)
            } else if (req.data.service == "multipath") {
                renderMultipath(data)
            } else if (req.data.service == "netbird") {
                renderNetbird(data)
            } else if (req.data.service == "nvme") {
                renderNvme(data)
            } else if (req.data.service == "ovn") {
                renderOvn(data)
            } else if (req.data.service == "tailscale") {
                renderTailscale(data)
            } else if (req.data.service == "usbip") {
                renderUsbip(data)
            } else {
                renderNotImplemented(data)
            }
        })
    }
</script>