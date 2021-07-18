    <!-- Modal -->
<div class="modal fade" id="modal-hosts-addImages" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Import Images</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <div class="form-group">
                <label> Hosts To Import Images To </label>
                <input id="hostsToImportImagesTo" class="form-control" />
            </div>
            <b> <u> Images Being Added </u> </b>
            <ul id="imagesToUpload" class="list-group">
                <li class="list-group-item">Second item</li>
                <li class="list-group-item">Third item</li>
            </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addImages">Add</button>
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
    });

    $("#modal-hosts-addImages").on("shown.bs.modal", function(){
        let imageHtml = "";
        $.each(imagesToImport, function(i, item){
            imageHtml += `<li class="list-group-item">${item.os} - ${item.alias}</li>`;
        });
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
