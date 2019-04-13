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
        <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label> Name </label>
                  <input type="text" name="name" id="newContainerName" class="form-control"/>
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label> Instance Type (Optional) </label>
                    <select id="newContainerInstanceType" class="form-control"></select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label> Profiles </label>
            <div class="alert alert-info">
                Only profiles on all hosts will appear
                <br/>
                Remember the default profile usually contains storage information &
                network details!
            </div>
            <input id="newContainerProfiles" type="text" class="form-control"/>
        </div>
        <div class="form-group">
            <label> Hosts To Create On </label>
            <input id="newContainerHosts" type="text" class="form-control"/>
        </div>
        <div class="form-group">
            <label> Image </label>
            <div class="alert alert-info">
                Currently an image needs to have been imported into atleast
                one server on the network to use it here! Images will be downloaded
                onto hosts that dont have the selected image.
            </div>
            <input id="newContainerImage" type="text" class="form-control"/>
        </div>
        <div class="form-group">
            <label> GPU's (Optional) </label>
            <select class="form-control" id="newContainerGpus" multiple>
                <option value="">Please select a host </option>
            </select>
            <div id="gpuWarning" class="alert alert-danger">
                We currently only support adding gpu's when creating a contaienr
                on one host.
            </div>
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
        tokenValue: "hostId",
        preventDuplicates: false,
        theme: "facebook",
        onAdd: function(token){
            let h = $("#newContainerHosts").tokenInput("get")
            if(h.length > 1){
                $("#gpuWarning").show();
                $("#newContainerGpus").hide();
            }else{
                let x = {hostIp: h[0].hostIp}
                ajaxRequest(globalUrls.hosts.gpu.getAll, x, (data)=>{
                    data =  $.parseJSON(data);
                    //TODO if len == 0
                    let gpus = "";
                    $.each(data, function(i, item){
                        gpus += `<option value="${item.id}">${item.product}</option>`
                    });
                    $("#newContainerGpus").empty().append(gpus);
                });
            }
        },
        onDelete: function(){
            let h = $("#newContainerHosts").tokenInput("get")
            if(h.length > 1){
                $("#gpuWarning").show();
                $("#newContainerGpus").hide();
            }else{
                if(h.length == 0){
                    $("#newContainerGpus").empty().append("<option value=''>Please select a host</option>");
                }
                $("#gpuWarning").hide();
                $("#newContainerGpus").show();
            }
        }
    });

    $("#modal-container-create").on("shown.bs.modal", function(){
        $("#gpuWarning").hide();
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
        let hosts = mapObjToSignleDimension($("#newContainerHosts").tokenInput("get"), "hostId");
        let image = $("#newContainerImage").tokenInput("get");
        let instanceType = $("#newContainerInstanceType").val();

        if(image.legnth == 0 || !image[0].hasOwnProperty("details")){
            makeToastr(JSON.stringify({state: "error", message: "Please select image"}));
            return false;
        }

        let gpus = [];

        if(hosts.length == 1){
            gpus = $("#newContainerGpus").val();
        }

        let x = {
            name: $("#newContainerName").val(),
            profileIds: profileIds,
            hosts: hosts,
            imageDetails: image[0]["details"],
            instanceType: instanceType,
            gpus: gpus
        };

        ajaxRequest(globalUrls.containers.create, x, function(data){
            let x = makeToastr(data);
            if(x.state == "error"){
                return false;
            }
            $("#modal-container-create").modal("toggle");
            loadContainerTreeAfter();
        });
    });
</script>
