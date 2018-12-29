<div id="imagesBox" class="boxSlide">
<!-- <h4> Container: <`span id="containerName"></span> </h4> -->
<div class="col-md-12">
    <div class="card">
      <div class="card-header" role="tab" id="container-imagesHeading">
        <h5>
          <a data-toggle="collapse" data-parent="#accordion" href="#container-imagesCollapse" aria-expanded="true" aria-controls="container-imagesCollapse">
            Images Overview
          </a>
        </h5>
      </div>

      <div id="container-imagesCollapse" class="collapsed show" aria-expanded="true" role="tabpanel" aria-labelledby="container-imagesHeading">
        <div id="imagesOverviewDetails" class="card-block">
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-danger deleteImages" id="deleteImagesBtn"> Delete </button>
                    <button class="btn btn-primary" id="importImagesBtn"> Import </button>
                </div>
            </div>
            <br/>
            <table class="table table-responsive" id="imagesTable">
                <thead>
                    <th>  </th>
                    <th> OS </th>
                    <th> Description </th>
                    <th> Release </th>
                    <th> Auto update </th>
                    <th> Created </th>
                    <th> Size </th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="remoteImagesTableBox">
                <table class="table table-responsive" id="remoteImagesTable">
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
        // dataTable = $("#remoteImagesTable").DataTable();
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
                    host: $(input).attr("data-host"),
                    fingerprint: $(input).attr("id")
                })
            }
        });
        let x = {
            imageData: data
        };
        ajaxRequest(globalUrls["images"].delete, x, function(data){
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

        ajaxRequest(globalUrls["images"].getLinuxContainersOrgImages, "POST", function(data){
            let x = $.parseJSON(data);
            if(x.hasOwnProperty("error")){
                return false;
            }
            let html = "<thead><tr>" +
                "<th> Import </th>"
            $.each(x["headers"], function(i, head){
                html += "<th>" + head + "</th>";
            });
            html += "</tr></thead><tbody>";
            $.each(x["data"], function(i, item){
                html += "<tr>";
                html += "<td> <input name='imageToImport' type='checkbox' value='" + item[0] + "/" + item[1] + "/" + item[2] + "'/></td>"
                $.each(item, function(o, p){
                    html += "<td>" + p + "</td>";
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
            dataTable = $("#remoteImagesTable").DataTable();
        });
    }

    function loadLocalImages()
    {
        if(dataTable !== null){
            // dataTable.destroy();
        }
        ajaxRequest(globalUrls["images"].getAll, null, function(data){
            let x = $.parseJSON(data);
            if(x.hasOwnProperty("error")){
                return false;
            }
            let trs = "";
            $.each(x, function(host, images){
                trs += "<tr class='alert alert-info'><td colspan='7' class='text-center'>" + host + "</td></tr>";
                $.each(images, function(i, data){
                    trs += "<tr>" +
                        "<td> <input data-host='" + host + "' id='" + data.fingerprint + "' name='imageSelect' type='checkbox' /></td>" +
                        "<td>" + data.properties.os + "</td>" +
                        "<td>" + data.properties.description + "</td>" +
                        "<td>" + data.properties.release + "</td>" +
                        "<td>" + data.auto_update + "</td>" +
                        "<td>" + moment(data.created_at).format("DD-MM-YYYY") + "</td>" +
                        "<td>" + formatBytes(data.size) + "</td>" +
                    "</tr>";
                });
            })

            $("#imagesTable > tbody").empty().append(trs);
            $("#profileBox, #containerBox, #cloudConfigBox, #overviewBox").hide();
            $("#imagesBox").show();
        });
    }

    $(document).on("click", ".viewImages", function(){
        loadLocalImages();
        changeActiveNav(".viewImages");
        var treeData = [
            {
                text: "Local Images",
                type: "localImages",
                icon: "fa fa-home",
                state: {
                    selected: true
                }
            },
            {
                text: "Linux Containers Org",
                type: "linuxContainersOrg",
                icon: "fa fa-cloud-download"
            }
        ]
        $('#jsTreeSidebar').treeview({
            data: treeData,         // data is not optional
            levels: 5,
            onNodeSelected: function(event, node) {
                if(node.type == "localImages"){
                    showLocalImages();
                }else if(node.type == "linuxContainersOrg"){
                    showRemoteImages();
                }
            }
        });
    });

</script>
