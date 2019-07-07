<div id="imagesBox" class="boxSlide">
<!-- <h4> Container: <`span id="containerName"></span> </h4> -->
<div class="col-md-12">
    <div class="card">
      <div class="card-header bg-info" role="tab" id="container-imagesHeading">
        <h5>
          <a class="text-white" data-toggle="collapse" data-parent="#accordion" href="#container-imagesCollapse" aria-expanded="true" aria-controls="container-imagesCollapse">
            Images Overview
          </a>
        </h5>
      </div>

      <div id="container-imagesCollapse" class="collapsed show" aria-expanded="true" role="tabpanel" aria-labelledby="container-imagesHeading">
        <div id="imagesOverviewDetails" class="card-block bg-dark table-responsive">
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-danger deleteImages" id="deleteImagesBtn"> Delete </button>
                    <button class="btn btn-primary" id="importImagesBtn"> Import </button>
                </div>
            </div>
            <br/>
            <table class="table table-dark table-bordered" id="imagesTable">
                <thead>
                    <th>  </th>
                    <th> OS </th>
                    <th> Description </th>
                    <th> Aliases </th>
                    <th> Release </th>
                    <th> Auto update </th>
                    <th> Created </th>
                    <th> Size </th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="remoteImagesTableBox">
                <table class="table table-dark table-bordered" id="remoteImagesTable">
                </table>
            </div>
        </div>
      </div>
    </div>
</div>
</div>
<script>

    var dataTable = null;

    $(function(){
        $("#importImagesBtn").hide();
    });

    function loadLocalImagesAfter(milSeconds = 2000)
    {
        setTimeout(function(){
            showLocalImages();
        }, milSeconds);
    }

    $("#imagesOverviewDetails").on("click", ".deleteImages", function(){
        let trs = $("#imagesTable > tbody > tr");
        let data = [];
        $(trs).each(function(){
            let input = $(this).find("input[name=imageSelect]");
            if($(input).is(":checked")){
                data.push({
                    hostId: $(input).attr("data-host"),
                    fingerprint: $(input).attr("id")
                })
            }
        });
        let x = {
            imageData: data
        };
        ajaxRequest(globalUrls.images.delete, x, function(data){
            makeToastr(data);
            loadLocalImagesAfter();
        });
    });

    $("#imagesOverviewDetails").on("click", "#importImagesBtn", function(){
        let imagesToImport = []
        $.each($("input[name=imageToImport]:checked"), function(){
            imagesToImport.push($(this).val());
        });
        imageAliasesToImport = imagesToImport;
        $("#modal-hosts-addImages").modal("show");
    });

    function showLocalImages(){
        loadLocalImages();
        $(".showLocal, .showRemotes").toggleClass("active");
        $("#deleteImagesBtn").show()
        $("#imagesTable").show();
        $("#importImagesBtn, #remoteImagesTableBox").hide()
    }

    function showRemoteImages(){
        $("#deleteImagesBtn").hide();
        $("#importImagesBtn").show()
        $("#remoteImagesTableBox").show();
        addBreadcrumbs(["Images", "Remote Images"], ["", "active"], false)
        ajaxRequest(globalUrls["images"].getLinuxContainersOrgImages, "POST", function(data){
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
            $("#imagesTable").hide();
            if(dataTable !== null){
                dataTable.clear();
                dataTable.destroy();
            }

            $("#remoteImagesTable").empty().append(html).show();
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

    function loadLocalImages()
    {
        addBreadcrumbs(["Images", "Local Images"], ["", "active"], false)
        ajaxRequest(globalUrls.images.getAll, null, function(data){
            let x = $.parseJSON(data);
            if(x.hasOwnProperty("error")){
                return false;
            }
            let trs = "";
            $.each(x, function(host, hostDetails){
                trs += `<tr class='bg-info'><td colspan='999' class='text-center'>${host}</td></tr>`;
                if(hostDetails.images.length == 0){
                    if(hostDetails.online){
                        trs += `<tr><td colspan="999" class="text-center"><b>No Images</b></td></tr>`;
                    }else{
                        trs += `<tr><td colspan="999" class="text-center"><b class="text-danger">Host Offline</b></td></tr>`;
                    }

                    return;
                }
                $.each(hostDetails.images, function(i, data){
                    let a = "-";
                    if(data.aliases.length > 0){
                        a = ""
                        $.each(data.aliases, function(i, item){
                            a += `${item.name} <br/>`;
                        });
                    }

                    trs += `<tr>
                        <td><input data-host='${hostDetails.hostId}' id='${data.fingerprint}' name='imageSelect' type='checkbox' /></td>
                        <td>${data.properties.os} </td>
                        <td>${data.properties.description} </td>
                        <td>${a}</td>
                        <td>${data.properties.release}</td>
                        <td>${data.auto_update}</td>
                        <td>${moment(data.created_at).format("DD-MM-YYYY")}</td>
                        <td>${formatBytes(data.size)}</td>
                    </tr>`;
                });
            })

            $("#imagesTable > tbody").empty().append(trs);
            $(".boxSlide").hide();
            $("#imagesBox").show();
        });
    }

    $(document).on("click", ".viewImages", function(){
        changeActiveNav(".viewImages");
        loadLocalImages();
        $("#sidebar-ul").empty().append(`
            <li class="nav-item imageLink active" data-type="localImages">
                <a class="nav-link" href="#">
                    <i class="nav-icon fa fa-home"></i> Local Images
                </a>
            </li>
            <li class="nav-item imageLink" data-type="linuxContainersOrg">
                <a class="nav-link" href="#">
                    <i class="nav-icon fas fa-cloud-download-alt"></i> Linux Containers Org
                </a>
            </li>
            `);
    });

    $("#sidebar-ul").on("click", ".imageLink", function(){
        let type = $(this).data("type");
        if(type == "localImages"){
            showLocalImages();
        }else if(type == "linuxContainersOrg"){
            showRemoteImages();
        }
    });

</script>
