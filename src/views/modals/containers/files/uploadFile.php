    <!-- Modal -->
<div class="modal fade" id="modal-container-files-upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Upload</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div method="POST" class="dropzone" id="fileUpload"></div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>

Dropzone.autoDiscover = false;

var uploadDropzone = null;

$("#modal-container-files-upload").on("shown.bs.modal", function(){
    if(uploadDropzone ==  null){
        uploadDropzone = $("div#fileUpload").dropzone({
            url: globalUrls.instances.files.uploadFiles,
            method: "POST",
            init: function() {
                this.on("sending", function(file, xhr, formData){
                    $.each(currentContainerDetails, function(key, item){
                        formData.append(key, item);
                    });
                    formData.append("path", currentPath);
                });
                this.on("complete", function (file) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        loadFileSystemPath(currentPath);
                    }
                });
            }

        });
    }
});
</script>
