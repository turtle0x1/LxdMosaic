    <!-- Modal -->
<div class="modal fade" id="modal-container-create" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Container</h5>
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
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Only profiles on all hosts will appear!
                            <br/>
                            <br/>
                            Remember the default profile usually contains storage information & network details!">
                        Profiles
                        <i class="fas fa-question-circle"></i>
                    </label>
                    <input id="newContainerProfiles" type="text" class="form-control"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label> Hosts To Create On </label>
                    <input id="newContainerHosts" type="text" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>  </label>
            <label
                data-toggle="tooltip"
                data-placement="top"
                title="Currently an image needs to have been imported into atleast
                one server on the network to use it here! Images will be downloaded
                onto hosts that dont have the selected image.">
                Image
                <i class="fas fa-question-circle"></i>
            </label>
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
        <label for="newContainerSettings">
            Settings(Optional)
            <button id="addNewContainerSetting" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i></button>
        </label>
        <table class="table table-borered" id="newContainerSettings">
            <thead>
                <tr>
                    <th> Setting </th>
                    <th> Value </th>
                    <th> Remove </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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
                let x = {hostId: h[0].hostId}
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

    var containerSettingRow = "";

    $("#modal-container-create").on("hide.bs.modal", function(){
        $("#newContainerName").val("");
        $("#newContainerGpus, #newContainerSettings > tbody").empty()
        $("#newContainerProfiles").tokenInput("clear");
        $("#newContainerHosts").tokenInput("clear");
        $("#newContainerImage").tokenInput("clear");
    });

    $("#modal-container-create").on("shown.bs.modal", function(){
        ajaxRequest(globalUrls.instances.settings.getAllAvailableSettings, {}, function(data){
            data = $.parseJSON(data);
            let selectHtml = "<select name='key' class='form-control containerSetting'><option value=''>Please Select</option>";
            $.each(data, function(i, item){
                selectHtml += `<option value='${item.key}' data-value="${item.value}">${item.key}</option>`;
            });
            selectHtml += `</select>`;
            containerSettingRow = `<tr>
                <td>${selectHtml}</td>
                <td><input name="value" class='form-control'/></td>
                <td><button class="removeSetting btn btn-danger"><i class='fas fa-trash'></i></button></td>
                </tr>`

        });

        $("#gpuWarning").hide();
        ajaxRequest(globalUrls.instances.instanceTypes.getInstanceTypes, {}, function(data){
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

    $("#modal-container-create").on("click", ".removeSetting", function(){
        $(this).parents("tr").remove();
    });

    $("#modal-container-create").on("click", "#addNewContainerSetting", function(){
        $("#newContainerSettings > tbody").append(containerSettingRow);
    });

    $("#modal-container-create").on("change", ".containerSetting", function(){
        $(this).parents("tr").find("input[name=value]").val($(this).find(":selected").data("value"));
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

        let config = {};
        let invalid = false;
        let message = "";
        $("#newContainerSettings > tbody > tr").each(function(){
            let keyInput = $(this).find("select[name=key]");
            let valueInput = $(this).find("input[name=value]");
            let key = keyInput.val();
            let value = valueInput.val();
            if(key == ""){
                keyInput.focus();
                invalid = true;
                message = "Please select setting";
            }else if(value == ""){
                valueInput.focus();
                invalid = true;
                message = "Please select value";
            }

            config[key] = value;
        });

        if(invalid){
            makeToastr(JSON.stringify({state: "error", message: message}));
            return false;
        }

        let x = {
            name: $("#newContainerName").val(),
            profileIds: profileIds,
            hosts: hosts,
            imageDetails: image[0]["details"],
            instanceType: instanceType,
            gpus: gpus,
            config: config
        };

        ajaxRequest(globalUrls.instances.create, x, function(data){
            let x = makeToastr(data);
            if(x.state == "error"){
                return false;
            }
            $("#modal-container-create").modal("hide");
            loadContainerTreeAfter();
        });
    });
</script>
