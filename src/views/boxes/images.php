<style>
.imageForImport {
    cursor: pointer;
}
</style>
<div id="imagesBox" class="boxSlide">
<!-- <h4> Container: <`span id="containerName"></span> </h4> -->
<div class="col-md-12" id="imageSplash">
    <div class="card bg-dark text-white">
      <div class="card-header" role="tab" id="container-imagesHeading">
        <h5 class="text-white">Search Remote Servers For Images</h5>
      </div>
      <div id="imagesOverviewDetails" class="card-body bg-dark table-responsive">
            <div id="remoteImagesTableBox">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                    <div class="input-group mb-2 me-sm-2">
                        <div class="input-group-text">Server</div>
                        <select id="searchImages-server" class="form-select">
                        </select>
                    </div>
                    <div class="input-group mb-2 me-sm-2">
                        <div class="input-group-text">Type</div>
                        <select id="searchImages-type" class="form-select">
                            <option value="" selected>All</option>
                            <option value="container">Container</option>
                            <option value="virtual-machine">Virtual Machine</option>
                        </select>
                    </div>
                    <div class="input-group mb-2 me-sm-2">
                        <div class="input-group-text">Arch</div>
                        <select id="searchImages-arch" class="form-select">
                            <option value="" selected>All</option>
                            <option value="amd64">amd64</option>
                            <option value="i386">i386</option>
                            <option value="armel">armel</option>
                            <option value="armhf">armhf</option>
                            <option value="arm64">arm64</option>
                            <option value="ppc64el">ppc64el</option>
                            <option value="powerpc">powerpc</option>
                            <option value="s390x">s390x</option>
                            <option value="riscv64">riscv64</option>
                        </select>
                    </div>
                    <button id="filterImages" class="btn btn-primary mb-2">
                        <i class="fas fa-search"></i>
                    </button>
                </div>


                <div class="mt-1" id="remoteImagesTable">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2" id="imagesInstructions">
                        <div><i class="fas fa-info-circle text-info me-2"></i>Select some images then click import!</div>
                        <div>
                            <button class="btn btn-outline-secondary float-end" id="importImagesBtn"> Import </button>
                        </div>
                    </div>

                    <div id="remoteImageList" class="row">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12" id="imageDetailsView">
    <div class="col-md-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
            <h4 id="imageName"> <u>
            </u></h4>
            <div class="btn-toolbar float-end">
              <div class="btn-group me-2">
                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Update Image" class="btn btn-sm btn-info" id="updateImageProperties">
                      <i class="fas fa-pencil-alt"></i>
                  </button>
                  <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete Image" class="btn btn-sm btn-danger" id="deleteImage">
                      <i class="fas fa-trash"></i>
                  </button>
              </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
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
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white">
                <div class="card-header">
                    <h4>Aliases
                        <button class="btn btn-sm btn-outline-primary float-end" id="createAlias">
                            <i class="fas fa-plus"></i>
                        </button>
                    </h4>
                </div>
                <div class="card-body" id="imageAliasesCardBody">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white">
                <div class="card-header">
                    <h4>Extended Details</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table  table-dark table-bordered" id="imageExtendedDetailsTable">
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>

    var currentImageDetails = {
        hostId: null,
        fingerprint: null
    }

    $(document).on("click", "#filterImages", function(){
        showRemoteImages()
    });

    function viewImages(){
        $(".sidebar-fixed").addClass("sidebar-lg-show");
        changeActiveNav(".viewImages")
        loadImageOverview();

        ajaxRequest('/api/InstanceSettings/ImageServers/GetAllImageServersController/all', {}, (data)=>{
            data = makeToastr(data)
            let options = "";
            if(data.servers.length == 0){
                options = `<option value="">No Image Servers</option>`;
                $("#searchImages-server, #searchImages-type, #searchImages-arch, #filterImages").attr("disabled", true);
            }else{
                options = `<option value="">Please select</option>` + data.servers.map(server => {
                    return `<option value="${server}">${server}</option>`
                })
                $("#searchImages-server, #searchImages-type, #searchImages-arch, #filterImages").attr("disabled", false);
            }
            $("#searchImages-server").empty().append(options)
        });
    }

    function makeImageName(image){
        let version = "Unknown Version";
        if(image.properties.hasOwnProperty("version")){
            version = image.properties.version;
        }else if(image.properties.hasOwnProperty("release")){
            version = image.properties.release;
        }
        return image.aliases.length == 0 ? `${image.properties.os}-${version}` : image.aliases[0].name;
    }

    function makeHostImagesSidebarHtml(hosthtml, host, id, selectedProfile = null, selectedHost = null){
        let disabled = "";

        if(host.hostOnline == false){
            disabled = "disabled text-warning text-strikethrough";
        }

        hosthtml += `<li class="mb-2" data-host-id="${host.hostId}">
            <a class="d-inline ${disabled}">
                <i class="fas fa-server"></i> ${host.alias}
            </a>`;

        if(host.hostOnline == true){
            hosthtml += `<button class="btn btn-outline-secondary btn-sm btn-toggle align-items-center rounded d-inline float-end me-2 toggleDropdown" data-bs-toggle="collapse" data-bs-target="#images-host-${id}" aria-expanded="true">
                <i class="fas fa-caret-left"></i>
            </button>`
        }else{
            return hosthtml;
        }

        hosthtml += `<div class="mt-2 bg-dark text-white collapse" id="images-host-${id}">
                <ul class="btn-toggle-nav hostContentList list-unstyled fw-normal pb-1 " style="display: inline;">`

        if(host.images.length == 0){
            hosthtml += `<li><a class="text-warning nav-link"><i class="fas fa-times me-2"></i>No Images</a></li>`
        }else{
            $.each(host.images, function(_, image){
                let active = host.hostId == currentImageDetails.hostId && image.fingerprint == currentImageDetails.fingerprint ? "active" : ""

                let imageName = makeImageName(image);

                let icon = image.hasOwnProperty("type") && image.type == "virtual-machine" ? "vr-cardboard" : "box";

                let variantIcon = "";
                if(image.properties.hasOwnProperty("variant") && image.properties.variant == "cloud"){
                    variantIcon = `<i class="fas fa-xs fa-cloud"></i>`
                }else if(image.properties.hasOwnProperty("variant") && image.properties.variant == "desktop"){
                    variantIcon = `<i class="fas fa-xs fa-desktop"></i>`
                }

                hosthtml += `<li class="nav-item">
                  <a class="nav-link ${active}" data-fingerprint="${image.fingerprint}" href="/images/${hostIdOrAliasForUrl(host.alias, host.hostId)}/${image.fingerprint}" data-navigo>
                    <i class="nav-icon fa fa-${icon}"></i>
                    ${imageName} ${variantIcon}
                  </a>
                </li>`;
            });
        }


        hosthtml += "</ul></li>";
        return hosthtml;
    }

    function loadImageOverviewAfter(milSeconds = 2000)
    {
        setTimeout(function(){
            loadImageOverview();
        }, milSeconds);
    }

    $(document).on("click", "#updateImageProperties", function(){
        $.confirm({
            title: `Updating Image!`,
            content: '',
            onOpen: function(){
                let body = this.$content
                ajaxRequest(globalUrls.images.proprties.getFiltertedList, currentImageDetails, (data)=>{
                    data = makeToastr(data);
                    let x = "";
                    $.each(data, (key, val)=>{
                        x += `<form action="" class="formName">
                        <div class="mb-2">
                            <b>${key.replace(/_/g, " ")}</b>
                            <input type="text" name="${key}" value="${val}" class="form-control" required />
                        </div>
                        </form>`
                    });
                    this.$content.append(x);
                });
            },
            buttons: {
                cancel: function () {
                    //close
                },
                update: {
                    text: 'Update',
                    btnClass: 'btn-warning',
                    action: function () {
                        let settings = {};
                        let invalid = false;
                        $.each(this.$content.find("input"), function(){
                            let key = $(this).attr("name");
                            let val = $(this).val().trim();
                            if(val == ""){
                                return false;
                            }
                            settings[key] = val;
                        });

                        if(invalid){
                            $.alert("All inputs must have a value");
                            return false;
                        }

                        let x = {
                            ...{
                                settings: settings
                            },
                            ...currentImageDetails
                        };

                        ajaxRequest(globalUrls.images.proprties.update, x, (data)=>{
                            data = makeToastr(data);
                            if(data.state == "error"){
                                return false;
                            }
                            $.each(settings, (key, val)=>{
                                $(`.td-${key}`).text(val);
                            });
                        });
                    }
                }
            }
        });

    });

    $(document).on("click", "#deleteImage", function(){
        let sidebarItem =$("#sidebar-ul").find(`.nav-link[href="/images/${hostIdOrAliasForUrl(currentImageDetails.alias, currentImageDetails.hostId)}/${currentImageDetails.network}"]`);
        let x = {
            imageData: [currentImageDetails]
        };
        ajaxRequest(globalUrls.images.delete, x, function(data){
            data = makeToastr(data);

            if(data.state == "error"){
                return false;
            }
            sidebarItem.remove();
            loadImageOverviewAfter();
            currentImageDetails = {
                hostId: null,
                fingerprint: null
            }
        });
    });

    $(document).on("click", "#importImagesBtn", function(e){
        e.preventDefault();
        let y = []
        $.each($(".imageForImport.bg-primary"), function(){
            y.push($(this).data());
        });
        imagesToImport = y;
        serverToImportFrom = $("#searchImages-server").val();
        $("#modal-hosts-addImages").modal("show");
        return false;
    });

    $(document).on("click", ".imageForImport", function(){
        $(this).toggleClass("bg-secondary");
        $(this).toggleClass("bg-primary");

        if($(".imageForImport.bg-primary").length == 0){
            $("#importImagesBtn").removeClass("btn-primary")
            $("#importImagesBtn").addClass("btn-outline-secondary")
        }else{
            $("#importImagesBtn").addClass("btn-primary")
            $("#importImagesBtn").removeClass("btn-outline-secondary")
        }
    });

    function showRemoteImages(){
        $("#importImagesBtn, #imagesInstructions").hide();
        $("#remoteImagesTable").show();
        $("#remoteImageList").show().empty().append('<h1 class="text-center"><i class="fa fa-cog fa-spin"></i></h1>')
        ajaxRequest(globalUrls.images.getLinuxContainersOrgImages, {
            urlKey: $("#searchImages-server").val(),
            searchType: $("#searchImages-type").val(),
            searchArch: $("#searchImages-arch").val(),
        }, function(data){
            let x = makeToastr(data);
            if(x.hasOwnProperty("error")){
                return false;
            }

            let html = "";
            let cols = Object.keys(x).length == 1 ? 12 : 4;
            $.each(x, (os, variants)=>{
                html += `<div class="col-md-${cols} border p-2 ps-3 osGroup"><h5 class="d-flex pb-1">${os}</h5>`;
                $.each(variants, (variant, versions)=>{
                    if(Object.values(versions).length > 0){
                        html += `<div class="d-block mb-2 mt-2"><i>${variant}</i></div>`
                        $.each(versions, (version, fingerPrint)=>{
                            html += `<div class="d-inline me-4 mb-3 mt-3">
                                <span class="badge bg-secondary imageForImport" data-variant="${variant}" data-fingerprint="${fingerPrint}" data-alias="${version}" data-os="${os}">
                                <i class="fas fa-image"></i>
                                ${version}
                                </span>
                            </div>`
                        });
                    }
                });
                html += `</div>`;
            });


            $("#remoteImageList").empty().append(html).find(".osGroup").find("div:eq(0)").removeClass("m-4").addClass("me-4");
            $("#importImagesBtn, #imagesInstructions").show();
        });
    }

    function loadImageOverview(changeView = true)
    {
        if(changeView){
            currentImageDetails = {}
        }
        addBreadcrumbs(["Images"], ["active"], false, ["/images"])
        ajaxRequest(globalUrls.images.getAll, null, function(data){
            data = makeToastr(data);
            let a = changeView == false ? "" : "active";
            let hosts = `
            <li class="mt-2 p-0">
                <a class="nav-link p-0 ${a}" href="/images" data-navigo>
                    <i class="fas fa-tachometer-alt"></i> Overview
                </a>
            </li>`;

            let id = 0;

            $.each(data.clusters, (clusterIndex, cluster)=>{
                hosts += `<li class="c-sidebar-nav-title text-success pt-2"><u>Cluster ${clusterIndex}</u></li>`;
                $.each(cluster.members, (_, host)=>{
                    hosts = makeHostImagesSidebarHtml(hosts, host, id)
                    id++
                })
            });

            hosts += `<li class="c-sidebar-nav-title text-success pt-2"><u>Standalone Hosts</u></li>`;

            $.each(data.standalone.members, (_, host)=>{
                hosts = makeHostImagesSidebarHtml(hosts, host, id)
                id++
            });

            $("#sidebar-ul").empty().append(hosts);
            if(changeView){
                $(".boxSlide, #imageDetailsView, #remoteImagesTable").hide();
                $("#imagesBox, #imageSplash").show();
            }
            router.updatePageLinks()
        });

    }

    $(document).on("click", ".deleteAlias", function(){
        let aliasBox = $(this).parents(".alias-box");
        let x = {...{name: aliasBox.data("name")},...currentImageDetails};
        ajaxRequest(globalUrls.images.aliases.delete, x, (data)=>{
            data = makeToastr(data);
            if(data.state == "error"){
                return false;
            }
            aliasBox.remove();
        });
    });

    $(document).on("click", ".renameAlias", function(){
        let aliasBox = $(this).parents(".alias-box");
        let currentName = aliasBox.data("name");

        $.confirm({
            title: `Renaming "${currentName}" Alias!`,
            content: `
            <form action="" class="formName">
                <div class="mb-2">
                    <label>New name</label>
                    <input type="text" name="name" class="form-control" required />
                </div>
            </form>`,
            buttons: {
                cancel: function () {
                    //close
                },
                rename: {
                    text: 'Rename',
                    btnClass: 'btn-blue',
                    action: function () {
                        let newName = this.$content.find('input[name=name]').val().trim();
                        if(newName == ""){
                            $.alert('provide a name');
                            return false;
                        }
                        let x = {
                            ...{
                                name: currentName,
                                newName: newName
                            },
                            ...currentImageDetails
                        };

                        ajaxRequest(globalUrls.images.aliases.rename, x, (data)=>{
                            data = makeToastr(data);
                            if(data.state == "error"){
                                return false;
                            }
                            aliasBox.data("name", newName);
                            aliasBox.find(".alias-name").text(newName);
                        });
                    }
                }
            }
        });
    });

    $(document).on("click", ".updateAliasDescription", function(){
        let aliasBox = $(this).parents(".alias-box");
        let currentName = aliasBox.data("name");
        let currentDescription = aliasBox.find(".alias-description").text().trim().trimStart()

        currentDescription = currentDescription == "No Description" ? "" : currentDescription;

        $.confirm({
            title: `Updating "${currentName}" Description!`,
            content: `
            <form action="" class="formName">
                <div class="mb-2">
                    <label>Description</label>
                    <textarea type="text" name="description" class="form-control">${currentDescription}</textarea>
                </div>
            </form>`,
            buttons: {
                cancel: function () {
                    //close
                },
                update: {
                    text: 'Update',
                    btnClass: 'btn-blue',
                    action: function () {
                        let description = this.$content.find('textarea[name=description]').val().trim();
                        let x = {
                            ...{
                                name: currentName,
                                description: description
                            },
                            ...currentImageDetails
                        };
                        ajaxRequest(globalUrls.images.aliases.updateDescription, x, (data)=>{
                            data = makeToastr(data);
                            if(data.state == "error"){
                                return false;
                            }

                            if(description == ""){
                                aliasBox.find(".alias-description").html("<b class=''>No Description</b>");
                            }else{
                                aliasBox.find(".alias-description").text(description);
                            }

                        });
                    }
                }
            }
        });
    });

    $(document).on("click", "#createAlias", function(){
        $.confirm({
            title: 'Create Image Alias!',
            content: `
            <form action="" class="formName">
                <div class="mb-2">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required />
                </div>
                <div class="mb-2">
                    <label>Description (Optional)</label>
                    <textarea class="form-control" name="description"></textarea>
                </div>
            </form>`,
            buttons: {
                cancel: function () {
                    //close
                },
                create: {
                    text: 'Create',
                    btnClass: 'btn-blue',
                    action: function () {
                        let name = this.$content.find('input[name=name]').val().trim();
                        if(name == ""){
                            $.alert('provide a name');
                            return false;
                        }
                        let description = this.$content.find("textarea[name=description]").val();
                        let x = {
                            ...{
                                name: name,
                                description: description
                            },
                            ...currentImageDetails
                        };
                        ajaxRequest(globalUrls.images.aliases.create, x, function(data){
                            data = makeToastr(data);
                            if(data.state == "error"){
                                return false;
                            }
                            $("#imageAliasesCardBody").append(`<div class="row alias-box border-bottom mb-1 pb-1" data-name=${name}>
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                                        <b><u class='alias-name'>${name}</u></b>
                                        <div class="btn-toolbar">
                                          <div class="btn-group me-2">
                                              <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Rename alias" class='renameAlias btn btn-outline-primary btn-sm'><i class='fas fa-pencil-alt'></i></button>
                                              <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Update description" class='updateAliasDescription btn btn-outline-info btn-sm'><i class='fas fa-edit'></i></button>
                                              <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete alias" class='deleteAlias btn btn-outline-danger btn-sm'><i class='fas fa-trash'></i></button>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 alias-description">
                                    ${description == "" ? "<b class='text-info'>No Description</b>"  : nl2br(description)}
                                </div>
                            </div>`);

                        });
                    }
                }
            }
        });
    });

    function viewImage(req){
        let d = {fingerprint: req.data.fingerprint, hostId: req.data.hostId, alias: hostsAliasesLookupTable[req.data.hostId]}
        currentImageDetails = d;

        if($("#sidebar-ul").find("[id^=images]").length == 0){
            loadImageOverview(false)
        }

        changeActiveNav(".viewImages")

        $(".boxSlide").hide();
        $("#imagesBox").show();

        $("#sidebar-ul").find(".active").removeClass("active");
        let i = $("#sidebar-ul").find(`.nav-link[href='/images/${hostIdOrAliasForUrl(d.alias, d.hostId)}/${req.data.fingerprint}']`)

        i.addClass("active")
        $("#imagesBox").show()
        $("#imageSplash").hide();
        $("#imageDetailsView").show();

        ajaxRequest(globalUrls.images.proprties.get, currentImageDetails, (data)=>{
            data = makeToastr(data);
            if(data.state == "error"){
                return false;
            }

            let image = data;
            let imageName =  makeImageName(image);
            addBreadcrumbs(["Images", d.alias, imageName], ["", "", "active"], false, ["/images"])

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
                    trs += `<div class="row alias-box border-bottom mb-1 pb-1" data-name=${alias.name}>
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                                <b><u class='alias-name'>${alias.name}</u></b>
                                <div class="btn-toolbar">
                                  <div class="btn-group me-2">
                                      <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Rename alias" class='renameAlias btn btn-outline-primary btn-sm'><i class='fas fa-pencil-alt'></i></button>
                                      <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Update description" class='updateAliasDescription btn btn-outline-info btn-sm'><i class='fas fa-edit'></i></button>
                                      <button data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete alias" class='deleteAlias btn btn-outline-danger btn-sm'><i class='fas fa-trash'></i></button>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 alias-description">
                            ${alias.description == "" ? "<b class='text-info'>No Description</b>"  : nl2br(alias.description)}
                        </div>
                    </div>`;
                });
            }else{
                trs = "<tr><td>No Aliases</td></tr>"
            }

            $("#imageAliasesCardBody").empty().append(trs);
            $("#imageAliasesCardBody").find('[data-bs-toggle="tooltip"]').tooltip({html: true});

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

                let prettyName = key.replace(/_/g, " ");

                trs += `<tr><th style='text-transform: capitalize;'>${prettyName}</th><td class='td-${key} text-break'>${val}</td></tr>`;
            });

            $("#imageExtendedDetailsTable > tbody").empty().append(trs);
            router.updatePageLinks()
        });
    }
</script>
