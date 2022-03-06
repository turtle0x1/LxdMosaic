    <!-- Modal -->
<div class="modal fade" id="modal-container-create" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Container</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="min-height: 60vh; max-height: 60vh;">
          <div class="row">
              <div class="col-md-3">
                  <ul class="list-group" id="createContainerStepList">
                      <li style="cursor: pointer" class="list-group-item active">1. Basic Details</li>
                      <li style="cursor: pointer" class="list-group-item">2. GPU's (Optional)</li>
                      <li style="cursor: pointer" class="list-group-item">3. Config (Optional)</li>
                  </ul>
              </div>
              <div class="col-md-9" style="max-height: 57vh; min-height: 57vh; overflow-y: scroll; border-left: 1px solid black;">
                  <div class="createContainerBox" data-step="1">
                    <div class="mb-2">
                          <label> Name </label>
                          <input type="text" name="name" id="newContainerName" class="form-control" autocomplete="off"/>
                      </div>
                    <div class="mb-2">
                        <label> Hosts </label>
                        <input id="newContainerHosts" type="text" class="form-control"/>
                    </div>
                    <div class="mb-2">
                        <label>Profiles</label>
                        <input id="newContainerProfiles" type="text" class="form-control"/>
                        <div class="text-muted text-sm">
                            <i class="fas fa-info-circle text-info me-2"></i>Only profiles on all hosts will appear! Dont forget one with a root disk.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label>Image</label>
                        <input id="newContainerImage" type="text" class="form-control"/>
                        <div class="text-muted text-sm">
                            <i class="fas fa-info-circle text-info me-2"></i>Only searchs your LXD servers, you may need to import an image from a remote first!
                        </div>
                    </div>
                    <div class="mb-2">
                        <label>Instance Type (Optional)</label>
                        <select id="newContainerInstanceType" class="form-select"></select>
                        <div class="text-muted text-sm">
                            <i class="fas fa-info-circle text-info me-2"></i>Used to impose resource limits!
                        </div>
                    </div>
                </div>
                <div class="createContainerBox" data-step="2" style="display: none;">
                    <div class="mb-2">
                        <label> GPU's (Optional) </label>
                        <select class="form-select" id="newContainerGpus" multiple>
                            <option value="">Please select a host </option>
                        </select>
                        <div id="gpuWarning" class="alert alert-danger">
                            We currently only support adding gpu's when creating a contaienr
                            on one host.
                        </div>
                    </div>
                </div>
                <div class="createContainerBox" data-step="3" style="display: none;">
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
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary createContainer" data-start="0">Create</button>
        <button type="button" class="btn btn-success createContainer" data-start="1">Create & Start</button>
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
        queryParam: "search",
        tokenLimit: 1,
        propertyToSearch: "description",
        theme: "facebook",
        tokenValue: "details",
        setExtraSearchParams: ()=>{
            return {type: "container"};
        }
    });

    function changeCreateContainerBox(newIndex){
        $(".createContainerBox").hide();
        $(`.createContainerBox[data-step='${(newIndex)}']`).show();
        $("#createContainerStepList").find(".active").removeClass("active");
        $("#createContainerStepList").find(`li:eq(${newIndex - 1})`).addClass("active");
    }

    $("#modal-container-create").on("click", "#createContainerStepList li", function(){
        changeCreateContainerBox($(this).index() + 1);
    });


    $("#newContainerHosts").tokenInput(globalUrls.hosts.search.search, {
        queryParam: "hostSearch",
        propertyToSearch: "host",
        tokenValue: "hostId",
        preventDuplicates: true,
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
                    $.each(data, function(i, gpu){
                        let name = gpu.hasOwnProperty("nvidia") && gpu.nvidia.hasOwnProperty("model") ? gpu.nvidia.model : gpu.vendor + " - " + gpu.product
                        gpus += `<option value="${gpu.pci_address}">${name}</option>`
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
        changeCreateContainerBox(1)
        $("#newContainerName").val("");
        $("#newContainerGpus, #newContainerSettings > tbody").empty()
        $("#newContainerProfiles").tokenInput("clear");
        $("#newContainerHosts").tokenInput("clear");
        $("#newContainerImage").tokenInput("clear");
    });

    $("#modal-container-create").on("shown.bs.modal", function(){
        changeCreateContainerBox(1)
        ajaxRequest(globalUrls.instances.settings.getAllAvailableSettings, {}, function(data){
            data = $.parseJSON(data);
            let selectHtml = "<select name='key' class='form-select containerSetting'><option value=''>Please Select</option>";
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
            $.each(data, function(provider, provDetails){
                h += `<optgroup label='${provider}'>`;
                if(provDetails.types.length == 0){
                    h += `<option value="">No Limits</option>`;
                }else{
                    $.each(provDetails.types, function(o, t){
                        h += `<option value="${t.instanceName}">${t.instanceName} (CPU: ${t.cpu}, Mem: ${t.mem}GB)</option>`;
                    })
                }
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

    $("#modal-container-create").on("click", ".createContainer", function(){
        let btn = $(this);

        btn.html('<i class="fa fa-cog fa-spin"></i>Creating..');
        $("#modal-container-create").find(".btn").attr("disabled", true);

        let profileIds = mapObjToSignleDimension($("#newContainerProfiles").tokenInput("get"), "profile");
        let hosts = mapObjToSignleDimension($("#newContainerHosts").tokenInput("get"), "hostId");
        let image = $("#newContainerImage").tokenInput("get");
        let instanceType = $("#newContainerInstanceType").val();


        let defaultBtnText = "Create";

        if(btn.data("start") == 1){
            defaultBtnText = "Create & Start";
        }

        if(image.length == 0 || !image[0].hasOwnProperty("details")){
            changeCreateContainerBox(1)
            btn.html(defaultBtnText);
            $("#modal-container-create").find(".btn").attr("disabled", false);
            makeToastr(JSON.stringify({state: "error", message: "Please select image"}));
            return false;
        }

        if(hosts.length == 0 || !image[0].hasOwnProperty("details")){
            changeCreateContainerBox(1)
            btn.html(defaultBtnText);
            $("#modal-container-create").find(".btn").attr("disabled", false);
            makeToastr(JSON.stringify({state: "error", message: "Please select host/s"}));
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
                invalid = keyInput
                message = "Please select setting";
                return false;
            }else if(value == ""){
                invalid = valueInput
                message = "Please select value";
                return false;
            }

            config[key] = value;
        });

        if(invalid){
            btn.html(defaultBtnText);
            changeCreateContainerBox(3)
            invalid.focus()
            $("#modal-container-create").find(".btn").attr("disabled", false);
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
            config: config,
            start: parseInt(btn.data("start"))
        };

        ajaxRequest(globalUrls.instances.create, x, function(data){
            let x = makeToastr(data);
            btn.html(defaultBtnText);
            $("#modal-container-create").find(".btn").attr("disabled", false);
            if(x.state == "error"){
                return false;
            }
            $("#modal-container-create").modal("hide");
            let currentLocation = router.getCurrentLocation().url;
            if(currentLocation == "" || currentLocation.includes("/instance/")){
                createDashboardSidebar();
            }
        });
    });
</script>
