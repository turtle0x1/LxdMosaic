    <!-- Modal -->
<div class="modal fade" id="modal-container-copy" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Copy Container <b><span class="copyModal-containerName"></span></b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>
            <b> Copying </b> <span class="copyModal-containerName"></span> <br/>
            <b> From Host: </b> <span id="copyModal-currentHost"></span>
        </h5>
        <div class="form-group">
            <b><label>New Host</label></b>
            <div class="alert alert-info">
                You can read more <a target="_blank" href="https://github.com/turtle0x1/LxdMosaic/wiki/Copying-between-instances">
                    here on github </a>
                about copying to another host, additional steps are required!
            </div>
            <input class="form-control validateName" maxlength="63" id="copyModal-newHost"/>
        </div>
        <div class="form-group">
            <b><label>New Container Name</label></b>
            <input class="form-control validateName" maxlength="63" id="copyModal-newName"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary copy">Copy</button>
      </div>
    </div>
  </div>
</div>
<script>

    $("#copyModal-newHost").tokenInput(globalUrls.hosts.search.search, {
        queryParam: "host",
        propertyToSearch: "host",
        tokenValue: "hostId",
        preventDuplicates: false,
        tokenLimit: 1,
        theme: "facebook"
    });

    $("#modal-container-copy").on("shown.bs.modal", function(){

        if(!$.isPlainObject(currentContainerDetails)){
            $("#modal-container-migrate").modal("toggle");
            alert("required variable isn't right");
            return false;
        }else if(typeof currentContainerDetails.container !== "string"){
            $("#modal-container-migrate").modal("toggle");
            alert("container isn't set");
            return false;
        }

        $("#copyModal-newHost").tokenInput("add", {
            id: null,
            host: currentContainerDetails.alias,
            hostId: currentContainerDetails.hostId
        });
        $(".copyModal-containerName").html(currentContainerDetails.container);
        $("#copyModal-currentHost").html(currentContainerDetails.alias);
    });

    $("#modal-container-copy").on("click", ".copy", function(){
        let d = $("#copyModal-newHost").tokenInput("get");

        if(d.length == 0){
            return false;
        }

        console.log(d);

        let x = $.extend({
            newContainer: $("#copyModal-newName").val(),
            newHostId: d[0].hostId
        }, currentContainerDetails);

        ajaxRequest(globalUrls.containers.copy, x, function(data){
            let x = makeToastr(data);
            if(x.state == "error"){
                return false;
            }
            loadContainerTreeAfter();
            $("#modal-container-copy").modal("toggle");
        });
    });
</script>
