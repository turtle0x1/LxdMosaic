<div id="imagesBox" class="boxSlide">
<!-- <h4> Container: <`span id="containerName"></span> </h4> -->
<div class="col-md-12" id="imageSplash">
    <div class="card bg-dark">
      <div class="card-header" role="tab" id="container-imagesHeading">
        <h5>
          <a class="text-white">
            <a href="https://images.linuxcontainers.org" target="_blank">Available To Import</a>
            <button class="btn btn-primary float-right" id="importImagesBtn"> Import </button>
          </a>
        </h5>
      </div>
      <div id="imagesOverviewDetails" class="card-body bg-dark table-responsive">
            <div id="remoteImagesTableBox">
                <table class="table mt-2 table-dark table-bordered" id="remoteImagesTable">
                </table>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12" id="imageDetailsView">
    <div class="col-md-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
            <h4 id="imageName"> <u>
            </u></h4>
            <div class="btn-toolbar float-right">
              <div class="btn-group mr-2">
                  <button data-toggle="tooltip" data-placement="bottom" title="Delete Profile" class="btn btn-danger" id="deleteImage">
                      <i class="fas fa-trash"></i>
                  </button>
              </div>
            </div>
        </div>
    </div>
    <div class="card-columns">
        <div class="card bg-dark text-white">
            <div class="card-header">
                <h4>Properties</h4>
            </div>
            <div class="card-body">
                <table class="table table-dark table-bordered" id="imagePropertiesTable">
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="card bg-dark text-white">
            <div class="card-header">
                <h4>Aliases</h4>
            </div>
            <div class="card-body">
                <table class="table table-dark table-bordered" id="imageAliasesTable">
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="card bg-dark text-white">
            <div class="card-header">
                <h4>Extended Details</h4>
            </div>
            <div class="card-body">
                <table class="table table-responsive table-dark table-bordered" id="imageExtendedDetailsTable">
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<script>

    var dataTable = null;

    var imageByHostId = {}

    var currentDetails = {
        hostId: null,
        fingerprint: null
    }

    $(document).on("click", ".viewImages", function(){
        $(".sidebar-fixed").addClass("sidebar-lg-show");
        changeActiveNav(".viewImages")
        loadImageOverview();
    });

    function makeImageName(image){
        let version = "Unknown Version";
        if(image.properties.hasOwnProperty("version")){
            version = image.properties.version;
        }else if(image.properties.hasOwnProperty("release")){
            version = image.properties.release;
        }
        return image.aliases.length == 0 ? `${image.properties.os}-${version}` : image.aliases[0];
    }

    function makeImagesHtml(hosthtml, host, selectedProfile = null, selectedHost = null){
        let disabled = "";

        hosthtml += `<li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle ${disabled}" href="#">
                <i class="fas fa-server"></i> ${host.hostAlias}
            </a>
            <ul class="nav-dropdown-items">`;

        $.each(host.images, function(_, image){
            let active = "";



            if(!imageByHostId.hasOwnProperty(host.hostId)){
                imageByHostId[host.hostId]  = {};
            }

            imageByHostId[host.hostId][image.fingerprint] = image;

            let imageName = makeImageName(image);

            let icon = image.hasOwnProperty("type") && image.type == "container" ? "box" : "vr-cardboard";

            hosthtml += `<li class="nav-item view-image ${active}"
                data-host-id="${host.hostId}"
                data-fingerprint="${image.fingerprint}"
                data-alias="${host.hostAlias}"
                >
              <a class="nav-link" href="#">
                <i class="nav-icon fa fa-${icon}"></i>
                ${imageName}
              </a>
            </li>`;
        });
        hosthtml += "</ul></li>";
        return hosthtml;
    }

    function loadImageOverviewAfter(milSeconds = 2000)
    {
        setTimeout(function(){
            loadImageOverview();
        }, milSeconds);
    }

    $(document).on("click", "#deleteImage", function(){
        let x = {
            imageData: [currentDetails]
        };
        ajaxRequest(globalUrls.images.delete, x, function(data){
            makeToastr(data);
            loadImageOverviewAfter();
            currentDetails = {
                hostId: null,
                fingerprint: null
            }
        });
    });

    $(document).on("click", "#importImagesBtn", function(e){
        e.preventDefault();
        let imagesToImport = []
        $.each($("input[name=imageToImport]:checked"), function(){
            imagesToImport.push($(this).val());
        });
        imageAliasesToImport = imagesToImport;
        $("#modal-hosts-addImages").modal("show");
        return false;
    });

    function showRemoteImages(){
        ajaxRequest(globalUrls.images.getLinuxContainersOrgImages, "POST", function(data){
            let x = $.parseJSON(data);
            if(x.hasOwnProperty("error")){
                return false;
            }
            let html = `<thead><tr>
                <th> Import </th>`
            $.each(x.headers, function(i, head){
                html += `<th>${head}</th>`;
            });
            html += "</tr></thead><tbody>";
            $.each(x.data, function(i, item){
                html += `<tr>
                    <td> <input name='imageToImport' type='checkbox' value='${item[0]}/${item[1]}/${item[2]}'/></td>`;
                $.each(item, function(o, p){
                    html += `<td>${p}</td>`;
                })
                html += "</tr>";
            });
            html += "</tbody>";
            if(dataTable !== null){
                dataTable.clear();
                dataTable.destroy();
            }

            $("#remoteImagesTable").empty().append(html)
            dataTable = $("#remoteImagesTable").DataTable({
                drawCallback: function( settings, json ) {
                    $('#remoteImagesTable td').css({
                        "background-color": "#454d55",
                        "color": "white"
                    });
                },
            });
        });
    }

    function loadImageOverview()
    {
        addBreadcrumbs(["Images"], ["", "active"], false)
        ajaxRequest(globalUrls.images.getAll, null, function(data){
            data = $.parseJSON(data);
            let a = "text-info";
            let hosts = `
            <li class="nav-item viewImages">
                <a class="nav-link ${a}" href="#">
                    <i class="fas fa-tachometer-alt"></i> Overview
                </a>
            </li>`;


            $.each(data.clusters, (clusterIndex, cluster)=>{
                hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Cluster ${clusterIndex}</u></li>`;
                $.each(cluster.members, (_, host)=>{
                    hosts = makeImagesHtml(hosts, host)
                })
            });

            hosts += `<li class="c-sidebar-nav-title text-success pl-1 pt-2"><u>Standalone Hosts</u></li>`;

            $.each(data.standalone.members, (_, host)=>{
                hosts = makeImagesHtml(hosts, host)
            });

            $("#sidebar-ul").empty().append(hosts);

            $(".boxSlide, #imageDetailsView").hide();
            $("#imagesBox, #imageSplash").show();
            showRemoteImages();
        });
    }

    $(document).on("click", ".view-image", function(){
        let d = $(this).data();
        currentDetails = d;
        $("#imageSplash").hide();
        $("#imageDetailsView").show();


        let image = imageByHostId[d.hostId][d.fingerprint];
        let imageName =  makeImageName(image);
        addBreadcrumbs([d.alias, imageName], ["", "active"], true)

        $("#imageName").text(imageName);

        let trs = "";

        $.each(image.properties, (name, value)=>{
            name = name.replace(/_/g, " ");
            trs += `<tr><th style='text-transform: capitalize;'>${name}</th><td>${value}</td></tr>`;
        });

        $("#imagePropertiesTable > tbody").empty().append(trs);

        trs = "";

        if(image.aliases.length){
            $.each(image.aliases, (_, alias)=>{
                trs += `<tr><td>${alias}</td></tr>`;
            });
        }else{
            trs = "<tr><td>No Aliases</td></tr>"
        }

        $("#imageAliasesTable > tbody").empty().append(trs);

        trs = "";

        let toGet = [
            "created_at",
            "last_used_at",
            "uploaded_at",
            "expires_at",
            "size",
            "auto_update",
            "public",
            "fingerprint",
            "architecture",
            "type",
        ]

        $.each(toGet, (_, key)=>{
            let val = image[key];

            if(["created_at", "last_used_at", "uploaded_at", "expires_at"].includes(key)){
                val = moment(val).format("llll");
            }else if(key == "size"){
                val = formatBytes(val);
            }

            key = key.replace(/_/g, " ");

            trs += `<tr><th style='text-transform: capitalize;'>${key}</th><td>${val}</td></tr>`;
        });

        $("#imageExtendedDetailsTable > tbody").empty().append(trs);

    });
</script>
