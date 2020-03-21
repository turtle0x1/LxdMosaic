    <!-- Modal -->
<div class="modal fade" id="modal-projects-create" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <b> Name </b>
              <input class="form-control" id="newProjectName"/>
          </div>
          <div class="form-group">
              <b> Description (Optional) </b>
              <textarea class="form-control" id="newProjectDescription"></textarea>
          </div>
          <div class="form-group">
              <b> Hosts </b>
              <input class="form-control" id="newProjectHosts"/>
          </div>
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
              </tbody>
          </table>
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
        queryParam: "host",
        propertyToSearch: "host",
        tokenValue: "hostId",
        preventDuplicates: false,
        theme: "facebook"
    });

    $("#modal-projects-create").on("hide.bs.modal",  function(){
        $("#modal-projects-create input, #modal-projects-create textarea").val("");
        $("#newProjectHosts").tokenInput("clear");
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
                "features.images": $("#modal-projects-create #imagesValue").val(),
                "features.profiles": $("#modal-projects-create #profilesValue").val()
            }
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
