    <!-- Modal -->
<div class="modal fade" id="modal-container-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Create Container</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label> Name </label>
            <input type="text" name="name" id="newContainerName" class="form-control"/>
        </div>
        <div class="form-group">
            <label> Profiles </label>
            <input id="newContainerProfiles" type="text" class="form-control"/>
        </div>
        <div class="alert alert-info">
            Only profiles on all hosts will appear
            <br/>
            Remember the default profile usually contains storage information &
            network details!
        </div>
        <div class="form-group">
            <label> Hosts To Create On </label>
            <input id="newContainerHosts" type="text" class="form-control"/>
        </div>
        <div class="form-group">
            <label> Image </label>
            <div class="alert alert-info">
                Currently an image needs to have been imported into atleast
                one server on the network to use it here!
            </div>
            <input id="newContainerImage" type="text" class="form-control"/>
            <br/>
            <div class="alert alert-info">
                If the image isn't available to the machine/s you are creating the
                container on it may cause the response to be slow while lxd fetches
                the image (may get from the internet)
            </div>
        </div>
        <div class="form-group">
            <label> Instance Type (Optional) </label>
            <select id="newContainerInstanceType" class="form-control"></select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="create">Create Container</button>
      </div>
    </div>
  </div>
</div>
<script>

    $("#newContainerProfiles").tokenInput(globalUrls.profiles.search.getCommonProfiles, {
        queryParam: "profile",
        propertyToSearch: "profile",
        theme: "facebook",
        tokenValue: "profile"
    });

    $("#newContainerImage").tokenInput(globalUrls.images.search.searchAllHosts, {
        queryParam: "image",
        tokenLimit: 1,
        propertyToSearch: "description",
        theme: "facebook",
        tokenValue: "details"
    });

    $("#newContainerHosts").tokenInput(globalUrls.hosts.search.search, {
        queryParam: "host",
        propertyToSearch: "host",
        preventDuplicates: false,
        theme: "facebook"
    });

    $("#modal-container-create").on("shown.bs.modal", function(){
        ajaxRequest(globalUrls.containers.instanceTypes.getInstanceTypes, {}, function(data){
            data = $.parseJSON(data);
            let h = "<option value=''>Please Select</option>";
            $.each(data, function(provider, templates){
                h += `<optgroup label='${provider}'>`;
                $.each(templates, function(o, t){
                    h += `<option value="${t.instanceName}">${t.instanceName} (CPU: ${t.cpu}, Mem: ${t.mem}GB)</option>`;
                })
                h += `</optgroup>`;
            });
            $("#newContainerInstanceType").empty().append(h);
        });
    });

    $("#modal-container-create").on("click", "#create", function(){
        let profileIds = mapObjToSignleDimension($("#newContainerProfiles").tokenInput("get"), "profile");
        let hosts = mapObjToSignleDimension($("#newContainerHosts").tokenInput("get"), "host");
        let image = $("#newContainerImage").tokenInput("get");
        let instanceType = $("#newContainerInstanceType").val();

        if(image.legnth == 0 || !image[0].hasOwnProperty("details")){
            makeToastr(JSON.stringify({state: "error", message: "Please select image"}));
            return false;
        }

        let x = {
            name: $("#newContainerName").val(),
            profileIds: profileIds,
            hosts: hosts,
            imageDetails: image[0]["details"],
            instanceType: instanceType
        };

        ajaxRequest(globalUrls["containers"].create, x, function(data){
            $("#modal-container-create").modal("toggle");
            loadContainerTreeAfter();
        });
    });
</script>
