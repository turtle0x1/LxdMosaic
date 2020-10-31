<div class="modal fade" id="modal-settings-setUserProject" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">User Projects</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-md-6 offset-md-3">
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
        ajaxRequest(globalUrls.settings.users.allowedProjects.getAllowed, setUserSettings, (data)=>{
            data = makeToastr(data);
            if(data.hasOwnProperty("state") && data.state == "error"){
                return false;
            }
            let trs = "";
            if(data.isAdmin === true){
                trs += `<tr>
                    <td colspan="999" class="text-center"><i class="fas fa-info-circle text-info mr-2"></i>Admin - can access all projects!</td>
                </tr>`
            }else if(data.projects.length == 0){
                trs += `<tr>
                    <td colspan="999" class="text-center"><i class="fas fa-info-circle text-info mr-2"></i>No Access</td>
                </tr>`
            }else{
                $.each(data.projects, (_, host)=>{
                    trs += `<tr><td colspan="999" class="text-center"><i class="fas fa-server text-info mr-2"></i>${host.alias}</td></tr>`
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

    });
</script>
