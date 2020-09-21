    <!-- Modal -->
<div class="modal fade" id="modal-projects-create" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <b> Name </b>
                      <input class="form-control" id="newProjectName"/>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <b> Description (Optional) </b>
                      <textarea class="form-control" id="newProjectDescription"></textarea>
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <b> Hosts </b>
                      <input class="form-control" id="newProjectHosts"/>
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                  <b> Features </b>
                  <table class="table table-bordered">
                      <thead>
                          <tr>
                              <th> Key </th>
                              <th> Description </th>
                              <th> Value </th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td> Networks </td>
                              <td> Separate set of networks for the project (Requires > LXD 4.6) </td>
                              <td>
                                  <select id="networksValue" class="form-control">
                                      <option value="true" selected>true</option>
                                      <option value="false">false</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> Images </td>
                              <td> Separate set of images and image aliases for the project </td>
                              <td>
                                  <select id="imagesValue" class="form-control">
                                      <option value="true" selected>true</option>
                                      <option value="false">false</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> Profiles </td>
                              <td> Separate set of profiles for the project </td>
                              <td>
                                  <select id="profilesValue" class="form-control">
                                      <option value="true" selected>true</option>
                                      <option value="false">false</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> Storage Volumes </td>
                              <td> Separate set of storage volumes for the project </td>
                              <td>
                                  <select id="storageValue" class="form-control">
                                      <option value="true" selected>true</option>
                                      <option value="false">false</option>
                                  </select>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12">

                  <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" id="enableRestrictions" value="1">
                      <label class="form-check-label" for="enableRestrictions"><b>Enable Restrictions?</b></label>
                  </div>
                  <div class="alert alert-danger" id="restrictionWarning">
                      This function is provided by LXD and not LXDMosaic
                      it is important to check your server supports these keys
                      before relying on them! Future versions will ensure your
                      sever is compatible before setting them!
                  </div>
                  <table class="table table-bordered" id="restrictionsTable">
                      <thead>
                          <tr>
                              <th> Key </th>
                              <th> Description </th>
                              <th> Value </th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td> Container Nesting </td>
                              <td> Prevents setting security.nesting=true </td>
                              <td>
                                  <select name="restricted.containers.nesting" class="form-control">
                                      <option value="block" class="default" selected>Block</option>
                                      <option value="allow">Allow</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> Privileged Containers </td>
                              <td>
                                    <b>"unpriviliged"</b>: prevents setting security.privileged=true.
                                    <br/>
                                    <b>"isolated"</b>: prevents setting security.privileged=true and also security.idmap.isolated=true.
                                    <br/>
                                    <b>"allow"</b>: no restriction apply.
                                </td>
                              <td>
                                  <select name="restricted.containers.privilege" class="form-control">
                                      <option value="unpriviliged" class="default" selected>Unpriviliged</option>
                                      <option value="isolated">Isolated</option>
                                      <option value="allow">Allow</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> Container Low Level Options </td>
                              <td> Prevents use of low-level container options like raw.lxc, raw.idmap, volatile, etc. </td>
                              <td>
                                  <select name="restricted.containers.lowlevel" class="form-control">
                                      <option value="block" class="default" selected>Block</option>
                                      <option value="allow">Allow</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> Virtual Machine Low Level Options </td>
                              <td> Prevents use of low-level virtual-machine options like raw.qemu, volatile, etc. </td>
                              <td>
                                  <select name="restricted.virtual-machines.lowlevel" class="form-control">
                                      <option value="block" class="default" selected>Block</option>
                                      <option value="allow">Allow</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> Disk Device Options </td>
                              <td>
                                  <b>"block"</b>: prevent use of disk devices except the root one.
                                  <br/>
                                  <b>"managed"</b>: allow use of disk devices only if "pool=" is set.
                                  <br/>
                                  <b>"allow"</b>: no restrictions apply. </td>
                              <td>
                                  <select name="restricted.devices.disk" class="form-control">
                                      <option value="block">Block</option>
                                      <option value="managed" class="default" selected>Managed</option>
                                      <option value="allow">Allow</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> GPU Devices </td>
                              <td> Prevents use of devices of type "gpu" </td>
                              <td>
                                  <select name="restricted.devices.gpu" class="form-control">
                                      <option value="block" class="default" selected>Block</option>
                                      <option value="allow">Allow</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> USB Devices </td>
                              <td> Prevents use of devices of type "usb" </td>
                              <td>
                                  <select name="restricted.devices.usb" class="form-control">
                                      <option value="block" class="default" selected>Block</option>
                                      <option value="allow">Allow</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> NIC Devices </td>
                              <td>
                                   <b>"block"</b> = prevent use of all network devices
                                   <br/>
                                   <b>"managed"</b> = allow use of network devices only if "network=" is set
                                   <br/>
                                   <b>"allow"</b> =  no restrictions apply
                              </td>
                              <td>
                                  <select name="restricted.devices.nic" class="form-control">
                                      <option value="block">Block</option>
                                      <option value="managed" class="default" selected>Managed</option>
                                      <option value="allow">Allow</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> Infiniband Devices </td>
                              <td> Prevents use of devices of type "infiniband" </td>
                              <td>
                                  <select name="restricted.devices.infiniband" class="form-control">
                                      <option value="block" class="default" selected>Block</option>
                                      <option value="allow">Allow</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> Unix Char Devices </td>
                              <td> Prevents use of devices of type "unix-char" </td>
                              <td>
                                  <select name="restricted.devices.unix-char" class="form-control">
                                      <option value="block" class="default" selected>Block</option>
                                      <option value="allow">Allow</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> Unix Block Devices </td>
                              <td> Prevents use of devices of type "unix-block" </td>
                              <td>
                                  <select name="restricted.devices.unix-block" class="form-control">
                                      <option value="block" class="default" selected>Block</option>
                                      <option value="allow">Allow</option>
                                  </select>
                              </td>
                          </tr>
                          <tr>
                              <td> Unix HotPlug Devices </td>
                              <td> Prevents use of devices of type "unix-hotplug" </td>
                              <td>
                                  <select name="restricted.devices.unix-hotplug" class="form-control">
                                      <option value="block" class="default" selected>Block</option>
                                      <option value="allow">Allow</option>
                                  </select>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="createProject">Create</button>
      </div>
    </div>
  </div>
</div>
<script>
    $("#newProjectHosts").tokenInput(globalUrls.hosts.search.search, {
        queryParam: "hostSearch",
        propertyToSearch: "host",
        tokenValue: "hostId",
        preventDuplicates: false,
        theme: "facebook"
    });

    $("#restrictionsTable, #restrictionWarning").hide();

    $("#modal-projects-create").on("hide.bs.modal",  function(){
        $("#modal-projects-create input, #modal-projects-create textarea").val("");
        $("#newProjectHosts").tokenInput("clear");
        $("#restrictionsTable").hide();
    });

    $("#restrictionsTable").on("change", ".form-control", function(){
        let selected = $(this).find(":selected");
        if(selected.hasClass("default")){
            $(this).removeClass("dirty");
            $(this).parents("tr").removeClass("bg-info");
        }else{
            $(this).addClass("dirty");
            $(this).parents("tr").addClass("bg-info");
        }
    });

    $("#modal-projects-create").on("change", "#enableRestrictions", function(){
        if($(this).is(":checked")){
            $("#restrictionsTable, #restrictionWarning").show();
        }else{
            $("#restrictionsTable, #restrictionWarning").hide();
        }
    });

    $("#modal-projects-create").on("click", "#createProject", function(){
        let hosts = mapObjToSignleDimension($("#newProjectHosts").tokenInput("get"), "hostId");

        let newProjectNameInput = $("#modal-projects-create #newProjectName");
        let description = $("#modal-projects-create #newProjectDescription").val();
        let projectName = newProjectNameInput.val();

        if(projectName == ""){
            makeToastr(JSON.stringify({state: "error", message: "Please provide new profile name"}));
            newProjectNameInput.focus();
            return false;
        }else if(hosts.length == 0){
            makeToastr(JSON.stringify({state: "error", message: "Please provide atleast one host"}));
            return false;
        }

        let x = {
            name: projectName,
            hosts: hosts,
            config: {
                "features.networks": $("#modal-projects-create #networksValue").val(),
                "features.images": $("#modal-projects-create #imagesValue").val(),
                "features.profiles": $("#modal-projects-create #profilesValue").val(),
                "features.storage.volumes": $("#modal-projects-create #storageValue").val()
            }
        }

        if($("#enableRestrictions").is(":checked")){
            x.config["restricted"] = true;
            $("#restrictionsTable .dirty").each(function(){
                x.config[$(this).attr("name")] = $(this).val();
            });
        }

        if(description !== ""){
            x.description = description;
        }


        ajaxRequest(globalUrls.projects.create, x, (data)=>{
            data = makeToastr(data);
            if(data.state == "success"){
                $("#modal-projects-create").modal("toggle");
                loadProjectView();
            }
        });
    });

</script>
