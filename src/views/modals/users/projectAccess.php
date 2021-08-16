<div class="modal fade" id="modal-settings-setUserProject" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">User Projects</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-md-6">
                  <h4>Grant Access</h4>
                  <div id="grantAccessAdminWarning">
                      <i class="fas fa-info-circle text-info me-2"></i>Admin - can access all projects!
                  </div>
                  <div id="grantAccessInputs">
                      <div class="mb-2">
                          <label>Host/s</label>
                          <small class="d-block"><i class="fas fa-info-circle text-info me-2"></i>Hosts without projects support will restrict the search to default</small>
                          <input id="grantAccesHosts" class="form-control"/>
                      </div>
                      <label>Project</label>
                      <table id="grantAccesProjectTable" class="table table-bordered">
                          <thead>
                              <tr>
                                  <th>Project</th>
                                  <th>Grant?</th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                      <button id="grantAccessButton" class="btn btn-primary float-end" disabled="disabled">
                          Grant Access
                      </button>
                  </div>
              </div>
              <div class="col-md-6 border-start">
                <h4>Current Access</h4>
                <table class="table table-bordered" id="projectAccessTable">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Revoke</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>
<script>
    var setUserSettings = {
        targetUser: null
    }

    var grantAccesProjectTableDefaultRow = '<tr><td colspan="999" class="text-center"><i class="fas fa-info-circle text-info me-2"></i>Pick host/s</td></tr>';

    function loadHostsProjects(token){
        let hostIds = mapObjToSignleDimension($("#grantAccesHosts").tokenInput("get"), "hostId");
        $("#grantAccesProjectTable > tbody").empty();
        if(hostIds.length == 0){
            $("#grantAccesProjectTable > tbody").append(grantAccesProjectTableDefaultRow);
            return false;
        }
        ajaxRequest(globalUrls.projects.search.getCommonToHosts, {hosts: hostIds}, (data)=>{
            data = makeToastr(data);
            if(data.hasOwnProperty("state") &&  data.state == "error"){
                return false;
            }
            let trs = "";
            $.each(data, (_, project)=>{
                trs += `<tr>
                    <td>${project}</td>
                    <td><input class="grantedProject" type="checkbox" value="${project}"/></td>
                </tr>`
            });
            $("#grantAccesProjectTable > tbody").empty().append(trs);
        });
    }

    $("#modal-settings-setUserProject").on("click", "#grantAccessButton", function(){
        let hostIds = mapObjToSignleDimension($("#grantAccesHosts").tokenInput("get"), "hostId");
        let projects = mapObjToSignleDimension($(".grantedProject:checked").get(), "value");
        let x = {...{
            hosts: hostIds,
            projects: projects
        }, ...setUserSettings};
        ajaxRequest(globalUrls.settings.users.allowedProjects.grantAcess, x, (data)=>{
            data = makeToastr(data);
            if(data.state == "error"){
                return false;
            }
            $("#grantAccessButton").attr("disabled", true);
            $("#modal-settings-setUserProject").trigger("show.bs.modal");
        });
    });

    $("#modal-settings-setUserProject").on("change", ".grantedProject", function(){
        if($(".grantedProject:checked").length > 0){
            $("#grantAccessButton").attr("disabled", false);
        }else{
            $("#grantAccessButton").attr("disabled", true);
        }
    });

    $("#modal-settings-setUserProject").on("click", ".revokeProjectAccsss", function(){
        let x = {...$(this).data(), ...setUserSettings};
        let tr = $(this).parents("tr");
        ajaxRequest(globalUrls.settings.users.allowedProjects.revokeAccess, x, (data)=>{
            data = makeToastr(data);
            if(data.state == "error"){
                return false;
            }
            tr.remove();
        })
    });

    $("#modal-settings-setUserProject").on("show.bs.modal",  function(){

        $("#grantAccesProjectTable > tbody").empty().append(grantAccesProjectTableDefaultRow);

        if($("#modal-settings-setUserProject").find(".token-input-list-facebook").length == 0){
            $("#grantAccesHosts").tokenInput(globalUrls.hosts.search.search, {
                queryParam: "hostSearch",
                propertyToSearch: "host",
                tokenValue: "hostId",
                preventDuplicates: true,
                theme: "facebook",
                onAdd: loadHostsProjects,
                onDelete: loadHostsProjects
            });
        }else{
            $("#grantAccesHosts").tokenInput("clear");
            $("#grantAccessButton").attr("disabled", true);
        }


        ajaxRequest(globalUrls.settings.users.allowedProjects.getAllowed, setUserSettings, (data)=>{
            data = makeToastr(data);
            if(data.hasOwnProperty("state") && data.state == "error"){
                return false;
            }
            let trs = "";

            if(data.isAdmin){
                $("#grantAccessAdminWarning").show();
                $("#grantAccessInputs").hide();
            }else{
                $("#grantAccessAdminWarning").hide();
                $("#grantAccessInputs").show();
            }

            if(data.isAdmin === true){
                trs += `<tr>
                    <td colspan="999" class="text-center"><i class="fas fa-info-circle text-info me-2"></i>Admin - can access all projects!</td>
                </tr>`
            }else if(data.projects.length == 0){
                trs += `<tr>
                    <td colspan="999" class="text-center"><i class="fas fa-info-circle text-info me-2"></i>No Access</td>
                </tr>`
            }else{
                $.each(data.projects, (_, host)=>{
                    trs += `<tr><td colspan="999" class="text-center"><i class="fas fa-server text-info me-2"></i>${host.alias}</td></tr>`
                    $.each(host.projects, (_, project)=>{
                        trs += `<tr>
                            <td>${project}</td>
                            <td><button data-host-id="${host.hostId}" data-project="${project}" class="btn btn-danger revokeProjectAccsss"><i class="fas fa-trash"></i></button></td>
                        </tr>`
                    });
                });
            }
            $("#projectAccessTable > tbody").empty().append(trs);
        });
    });

    $("#modal-settings-setUserProject").on("hide.bs.modal",  function(){
        $("#grantAccesProjectTable > tbody").empty().append(grantAccesProjectTableDefaultRow);
        $("#grantAccesHosts").tokenInput("clear");
        $("#grantAccessButton").attr("disabled", true);
        $("#grantAccessAdminWarning").hide();
        $("#grantAccessInputs").hide();
    });
</script>
