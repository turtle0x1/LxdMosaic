    <!-- Modal -->
<div class="modal fade" id="modal-hosts-edit" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Host</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label> Alias </label>
              <input class="form-control" id="alias" />
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="edit">Save</button>
      </div>
    </div>
  </div>
</div>
<script>

var editHostDetailsObj = {
    hostId: null
}

$("#modal-hosts-edit").on("shown.bs.modal", function(){
    if(!$.isNumeric(editHostDetailsObj.hostId)){
        makeToastr(JSON.stringify({state: "error", message: "Developer Fail - Please provide host id"}));
        $("#modal-hosts-edit").modal("toggle");
        return false;
    }
});

$("#modal-hosts-edit").on("click", "#edit", function(){
    let aInput = $("#modal-hosts-edit #alias");
    let alias = aInput.val();
    if(alias == ""){
        makeToastr(JSON.stringify({error: "Please provide the alias"}));
        return false;
    }

    let x = $.extend({alias: alias}, editHostDetailsObj);

    ajaxRequest(globalUrls.hosts.alias.update, x, function(data){
        data = makeToastr(data);
        if(data.state == "error"){
            return false;
        }
        aInput.val("");
        loadDashboard();
        $("#modal-hosts-edit").modal("hide");
    });
});

</script>
