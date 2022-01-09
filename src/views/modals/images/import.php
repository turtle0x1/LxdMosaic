    <!-- Modal -->
<div class="modal fade" id="modal-hosts-addImages" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="">Start Image Import</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <div class="mb-2">
                <label> Hosts To Import To </label>
                <input id="hostsToImportImagesTo" class="form-control" />
            </div>
            <label> Images To Import </label>
            <ul id="imagesToUpload" class="list-group">
            </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addImages">Import</button>
      </div>
    </div>
  </div>
</div>
<script>
    var imagesToImport = [];
    var serverToImportFrom = "";

    $("#hostsToImportImagesTo").tokenInput(globalUrls.hosts.search.search, {
        queryParam: "hostSearch",
        propertyToSearch: "host",
        tokenValue: "hostId",
        theme: "facebook",
        preventDuplicates: false
    });

    $("#modal-hosts-addImages").on("hide.bs.modal", function(){
        $("#hostsToImportImagesTo").tokenInput("clear");
        $("#imagesToUpload").empty()
    });

    $("#modal-hosts-addImages").on("shown.bs.modal", function(){
        let imageHtml = "";
        if(imagesToImport.length > 0){
            $.each(imagesToImport, function(i, item){
                imageHtml += `<li class="list-group-item">${item.os} - ${item.alias} - ${item.variant}</li>`;
            });
            $("#addImages").attr("disabled", false)
        }else {
            imageHtml = `<li class="list-group-item"><i class="fa fa-exclamation-triangle me-2"></i>No images selected!</li>`;
            $("#addImages").attr("disabled", true)
        }

        $("#imagesToUpload").empty().append(imageHtml);
    });

    $("#modal-hosts-addImages").on("click", "#addImages", function(){
        let p = mapObjToSignleDimension($("#hostsToImportImagesTo").tokenInput("get"), "hostId");

        let x = {
            aliases: imagesToImport,
            hosts: p,
            urlKey: serverToImportFrom
        }

        ajaxRequest(globalUrls.images.import, x, function(data){
            let x = makeToastr(data);
            if(x.hasOwnProperty("error")){
                return false;
            }
            $("#modal-hosts-addImages").modal("toggle");
        });
    });
</script>
