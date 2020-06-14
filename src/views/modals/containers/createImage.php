    <!-- Modal -->
<div class="modal fade" id="modal-container-createImage" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Create Image</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label> Image Alias </label>
              <input class="form-control" name="alias" />
          </div>
          <div>
              Public
              <br/>
              <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="public" id="yesRadio" value="1">
                  <label class="form-check-label" for="yesRadio">Yes</label>
              </div>
              <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="public" id="noRadio" value="0">
                  <label class="form-check-label" for="noRadio">No</label>
              </div>
          </div>
          <div class="form-group">
              <label> OS (Optional) </label>
              <input class="form-control" name="os" />
          </div>
          <div class="form-group">
              <label> Image Description (Optional) </label>
              <textarea class="form-control" name="description"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="createImage">Create</button>
      </div>
    </div>
  </div>
</div>
<script>

    var reamingSettingSelectOptions = "";

    $("#modal-container-createImage").on("hide.bs.modal", function(){
        $("#editSettings-currentHost").text("");
        $("#editSettings-list").empty();
    });

    $("#modal-container-createImage").on("click", "#createImage", function(){
        let aInput = $("#modal-container-createImage input[name=alias]");
        let alias = aInput.val().trim();

        let pub = parseInt($("#modal-container-createImage").find("input[name=public]:checked").val())

        if(alias == ""){
            $.alert("Please provide an alias");
            return false;
        }

        let os = $("#modal-container-createImage input[name=os]").val().trim()
        let description = $("#modal-container-createImage textarea[name=description]").val().trim()

        let x = {...{imageAlias: alias, os: os, public: pub, description: description}, ...currentContainerDetails};
        ajaxRequest(globalUrls.instances.exportImage, x, (data)=>{
            data = makeToastr(data);
            if(data.hasOwnProperty("state") && data.state == "error"){
                return false;
            }
            $("#modal-container-createImage").modal("hide");

        });
    });

    $("#modal-container-createImage").on("hide.bs.modal", function(){
        $("#modal-container-createImage input[name=alias]").val("");
        $("#modal-container-createImage input[name=os]").val("")
        $("#modal-container-createImage textarea[name=description]").val("")
        $("#modal-container-createImage #yesRadio").prop("checked", false);
        $("#modal-container-createImage #noRadio").prop("checked", true);

    });

    $("#modal-container-createImage").on("shown.bs.modal", function(){

        reamingSettingSelectOptions = "";

        if(!$.isPlainObject(currentContainerDetails)){
            $("#modal-container-createImage").modal("toggle");
            alert("required variable isn't right");
            return false;
        }else if(typeof currentContainerDetails.container !== "string"){
            $("#modal-container-createImage").modal("toggle");
            alert("container isn't set");
            return false;
        }
    });


</script>
