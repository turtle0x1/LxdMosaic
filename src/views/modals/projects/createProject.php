    <!-- Modal -->
<div class="modal fade" id="modal-projects-create" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-project-diagram me-2"></i>Create Project</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="max-height: 60vh; padding-top: 1px;">
          <div class="row">
              <div class="col-md-3 pt-3">
                    <ul class="list-group" id="createProjectStepList">
                        <li style="cursor: pointer" class="list-group-item active">1. Basic Details</li>
                        <li style="cursor: pointer" class="list-group-item">2. Limits (Optional)</li>
                        <li style="cursor: pointer" class="list-group-item">3. Restrictions (Optional)</li>
                    </ul>
              </div>
              <div class="col-md-9 p-3" style="max-height: 57vh; min-height: 57vh; overflow-y: scroll; border-left: 1px solid black;">
                  <div class="createProjectStep" data-step="1">
                      <div class="row">
                          <div class="col-md-6">
                              <div class="mb-2">
                                  <b> Name </b>
                                  <input class="form-control" id="newProjectName"/>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="mb-2">
                                  <b> Description (Optional) </b>
                                  <textarea class="form-control" id="newProjectDescription"></textarea>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-12">
                              <div class="mb-2">
                                  <b> Hosts </b>
                                  <small><i class="fas fa-info-circle me-2 text-info"></i>Only finds hosts that support projects</small>
                                  <input class="form-control" id="newProjectHosts"/>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-12">
                              <b> Features </b>
                              <table class="table table-bordered border-secondary">
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
                                              <select id="networksValue" class="form-select">
                                                  <option value="true" selected>true</option>
                                                  <option value="false">false</option>
                                              </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Images </td>
                                          <td> Separate set of images and image aliases for the project </td>
                                          <td>
                                              <select id="imagesValue" class="form-select">
                                                  <option value="true" selected>true</option>
                                                  <option value="false">false</option>
                                              </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Profiles </td>
                                          <td> Separate set of profiles for the project </td>
                                          <td>
                                              <select id="profilesValue" class="form-select">
                                                  <option value="true" selected>true</option>
                                                  <option value="false">false</option>
                                              </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Storage Volumes </td>
                                          <td> Separate set of storage volumes for the project </td>
                                          <td>
                                              <select id="storageValue" class="form-select">
                                                  <option value="true" selected>true</option>
                                                  <option value="false">false</option>
                                              </select>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
                  <div class="createProjectStep" data-step="2" style="display: none;">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="text-center" id="limitsWarning">
                                  <i class="fas fa-exclamation-triangle text-warning me-2"></i>Not all LXD versions support all keys.
                              </div>
                              <table class="table table-bordered  border-secondary" id="createProjectLimitsTable">
                                  <thead>
                                      <tr>
                                          <th> Key </th>
                                          <th> Description </th>
                                          <th> Value </th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                          <td> Instance Limit </td>
                                          <td> Maximum number of total instances that can be created in the project </td>
                                          <td>
                                              <input name="limits.instances" class="form-control" />
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Container Limit </td>
                                          <td> Maximum number of containers that can be created in the project </td>
                                          <td>
                                              <input name="limits.containers" class="form-control" />
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Virtual Machine Limit </td>
                                          <td> Maximum number of VMs that can be created in the project </td>
                                          <td>
                                              <input name="limits.virtual-machines" class="form-control" />
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Processes Limit </td>
                                          <td> Maximum value for the sum of individual "limits.processes" configs set on the instances of the project </td>
                                          <td>
                                              <input name="limits.processes" class="form-control" />
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> CPU Time </td>
                                          <td> Maximum value for the sum of individual "limits.cpu" configs set on the instances of the project </td>
                                          <td>
                                              <input name="limits.cpu" class="form-control" />
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Disk Usage </td>
                                          <td> Maximum value of aggregate disk space used by all instances volumes, custom volumes and images of the project </td>
                                          <td>
                                              <input name="limits.disk" class="form-control" />
                                          </td>
                                      </tr>

                                      <tr>
                                          <td> Memory Limit </td>
                                          <td> Maximum value for the sum of individual "limits.memory" configs set on the instances of the project </td>
                                          <td>
                                              <input name="limits.memory" class="form-control" />
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Networks Limit </td>
                                          <td> Maximum value for the number of networks this project can have </td>
                                          <td>
                                              <input name="limits.networks" class="form-control" />
                                          </td>
                                      </tr>
                                  </tobdy>
                              </table>
                          </div>
                      </div>
                  </div>
                  <div class="createProjectStep" data-step="3" style="display: none;">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="text-center" id="restrictionWarning">
                                  <i class="fas fa-exclamation-triangle text-warning me-2"></i>Not all LXD versions support all keys.
                              </div>
                              <table class="table table-bordered border-secondary" id="restrictionsTable">
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
                                              <select name="restricted.containers.nesting" class="form-select">
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
                                              <select name="restricted.containers.privilege" class="form-select">
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
                                              <select name="restricted.containers.lowlevel" class="form-select">
                                                  <option value="block" class="default" selected>Block</option>
                                                  <option value="allow">Allow</option>
                                              </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Virtual Machine Low Level Options </td>
                                          <td> Prevents use of low-level virtual-machine options like raw.qemu, volatile, etc. </td>
                                          <td>
                                              <select name="restricted.virtual-machines.lowlevel" class="form-select">
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
                                              <select name="restricted.devices.disk" class="form-select">
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
                                              <select name="restricted.devices.gpu" class="form-select">
                                                  <option value="block" class="default" selected>Block</option>
                                                  <option value="allow">Allow</option>
                                              </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> USB Devices </td>
                                          <td> Prevents use of devices of type "usb" </td>
                                          <td>
                                              <select name="restricted.devices.usb" class="form-select">
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
                                              <select name="restricted.devices.nic" class="form-select">
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
                                              <select name="restricted.devices.infiniband" class="form-select">
                                                  <option value="block" class="default" selected>Block</option>
                                                  <option value="allow">Allow</option>
                                              </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Unix Char Devices </td>
                                          <td> Prevents use of devices of type "unix-char" </td>
                                          <td>
                                              <select name="restricted.devices.unix-char" class="form-select">
                                                  <option value="block" class="default" selected>Block</option>
                                                  <option value="allow">Allow</option>
                                              </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Unix Block Devices </td>
                                          <td> Prevents use of devices of type "unix-block" </td>
                                          <td>
                                              <select name="restricted.devices.unix-block" class="form-select">
                                                  <option value="block" class="default" selected>Block</option>
                                                  <option value="allow">Allow</option>
                                              </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Unix HotPlug Devices </td>
                                          <td> Prevents use of devices of type "unix-hotplug" </td>
                                          <td>
                                              <select name="restricted.devices.unix-hotplug" class="form-select">
                                                  <option value="block" class="default" selected>Block</option>
                                                  <option value="allow">Allow</option>
                                              </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> PCI Devices </td>
                                          <td> Prevents use of devices of type "pci" </td>
                                          <td>
                                              <select name="restricted.devices.pci" class="form-select">
                                                  <option value="block" class="default" selected>Block</option>
                                                  <option value="allow">Allow</option>
                                              </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Proxy Devices </td>
                                          <td> Prevents use of devices of type "proxy" </td>
                                          <td>
                                              <select name="restricted.devices.proxy" class="form-select">
                                                  <option value="block" class="default" selected>Block</option>
                                                  <option value="allow">Allow</option>
                                              </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Backups </td>
                                          <td> Prevents the creation of any instance or volume backups. </td>
                                          <td>
                                              <select name="restricted.backups" class="form-select">
                                                  <option value="block" class="default" selected>Block</option>
                                                  <option value="allow">Allow</option>
                                              </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Snapshots </td>
                                          <td> Prevents the creation of any instance or volume snapshots. </td>
                                          <td>
                                              <select name="restricted.snapshots" class="form-select">
                                                  <option value="block" class="default" selected>Block</option>
                                                  <option value="allow">Allow</option>
                                              </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td> Cluster Target </td>
                                          <td> Prevents direct targeting of cluster members when creating or moving instances. </td>
                                          <td>
                                              <select name="restricted.cluster.target" class="form-select">
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
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="createProject">Create</button>
        <button type="button" class="btn btn-primary" id="nextCreateProjectBox">Next</button>
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
        theme: "facebook",
        setExtraSearchParams: ()=>{
            return {extensionRequirements: ["projects"]}
        }
    });

    $("#modal-projects-create").on("show.bs.modal",  function(){
        $("#createProjectStepList li:eq(0)").trigger("click");
    });

    $("#modal-projects-create").on("hide.bs.modal",  function(){
        $("#modal-projects-create input, #modal-projects-create textarea").val("");
        $("#newProjectHosts").tokenInput("clear");
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

    $("#modal-projects-create").on("click", "#createProjectStepList li", function(){
        changeCreateProjectBox($(this).index() + 1);
    });

    $("#modal-projects-create").on("click", "#nextCreateProjectBox", function(){
        let currentStep = $(".createProjectStep:visible").data("step")
        changeCreateProjectBox(currentStep + 1)
    });

    function changeCreateProjectBox(newIndex){
        let maxSteps = 3;
        if(newIndex == maxSteps){
            $("#nextCreateProjectBox").hide()
        }else if(newIndex < maxSteps){
            $("#nextCreateProjectBox").show()
        }
        $(".createProjectStep").hide();
        $(`.createProjectStep[data-step='${(newIndex)}']`).show();
        $("#createProjectStepList").find(".active").removeClass("active");
        $("#createProjectStepList").find(`li:eq(${newIndex - 1})`).addClass("active");
    }

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

        if($("#restrictionsTable .dirty").length > 0){
            x.config["restricted"] = true;
            $("#restrictionsTable .dirty").each(function(){
                x.config[$(this).attr("name")] = $(this).val();
            });
        }

        let limits = {};

        $("#createProjectLimitsTable > tbody input").each(function(){
            let input = $(this);
            if(input.val() !== ""){
                limits[input.attr("name")] = input.val();
            }
        });

        if(Object.keys(limits).length > 0){
             x.config = Object.assign(x.config, limits)
        }

        if(description !== ""){
            x.description = description;
        }

        ajaxRequest(globalUrls.projects.create, x, (data)=>{
            data = makeToastr(data);
            if(data.state == "success"){
                $("#modal-projects-create").modal("toggle");
            }
        });
    });

</script>
