    <!-- Modal -->
<div class="modal fade" id="modal-cloudConfig-deploy" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Deploy Cloud Config</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="deployCloudConfig">Deploy</button>
      </div>
    </div>
  </div>
</div>
<script>

var deployCloudConfigObj = {
    cloudConfigId : null
}

var _deployCloudConfigContents = `<div class="mb-2">
              <label> Instance Name </label>
              <input class="form-control" name="containerName" />
          </div>
          <div class="mb-2">
              <label> Where To Deploy </label>
              <select class="form-select" id="deployCloudConfigHosts"></select>
          </div>
          <div class="mb-2">
              <label> Profile Name (Optional) </label>
              <input class="form-control" name="profileName" />
          </div>

          <div class="mb-2">
              <label
                  data-toggle="tooltip"
                  data-bs-placement="top"
                  title="Only profiles on all hosts will appear!
                      <br/>
                      <br/>
                      Remember the default profile usually contains storage information & network details!">
                  Additional Profiles
                  <i class="fas fa-question-circle"></i>
              </label>
              <input class="form-control" id="deployCloudConfigProfiles"/>
          </div>
          <div class="mb-2">
              <label> GPU's (Optional) </label>
              <select class="form-select" id="deployContainerGpu" multiple>
                  <option value="">Please select a host </option>
              </select>
          </div>
`

$("#modal-cloudConfig-deploy").on("change", "#deployCloudConfigHosts", function() {
    let hostId = $(this).find(":selected").parents("optgroup").attr("id")
    if($.isNumeric(hostId)){
        ajaxRequest(globalUrls.hosts.gpu.getAll, {hostId}, (data)=>{
            data =  $.parseJSON(data);
            //TODO if len == 0
            let gpus = "";
            $.each(data, function(i, item){
                gpus += `<option value="${item.pci_address}">${item.product}</option>`
            });
            $("#deployContainerGpu").empty().append(gpus);
        });
    }else{
        $("#deployContainerGpu").empty().append("<option value=''>Please select a host</option>");
    }
})

$("#modal-cloudConfig-deploy").on("hidden.bs.modal", function(){
    $("#modal-cloudConfig-deploy .modal-body").empty().append(_deployCloudConfigContents);
    $("#modal-cloudConfig-deploy .modal-footer").show()
    $("#modal-cloudConfig-deploy .modal-dialog").removeClass("modal-xl")
});

$("#modal-cloudConfig-deploy").on("show.bs.modal", function(){
    $("#modal-cloudConfig-deploy .modal-dialog").removeClass("modal-xl")
    $("#modal-cloudConfig-deploy .modal-body").empty().append(_deployCloudConfigContents);
    if(!$.isNumeric(deployCloudConfigObj.cloudConfigId)){
        makeToastr(JSON.stringify({state: "error", message: "Developer fail - set cloud config id to open this modal"}));
        return false;
    }

    ajaxRequest(globalUrls.projects.getAllFromHosts, {}, function(data){
        data = $.parseJSON(data);
        let options = "<option value=''>Please select</option>";
        $.each(data.clusters, (clusterIndex, cluster)=>{
            options += `<li class="c-sidebar-nav-title text-success ps-1 pt-2"><u>Cluster ${clusterIndex}</u></li>`;
            $.each(cluster.members, (_, host)=>{
                if(host.hostOnline == 0){
                    return true;
                }
                options += `<optgroup id="${host.hostId}" label="${host.alias}">`
                $.each(host.projects, (project, _)=>{
                    options += `<option value="${project}">${project}</option>`
                });
                options += `</optgroup>`
            })
        });

        $.each(data.standalone.members, (_, host)=>{
            if(host.hostOnline == 0){
                return true;
            }
            options += `<optgroup id="${host.hostId}" label="${host.alias}">`
            $.each(host.projects, (_, project)=>{
                options += `<option value="${project}">${project}</option>`
            });
            options += `</optgroup>`
        });
        $("#deployCloudConfigHosts").empty().append(options);
    });

    $("#deployCloudConfigProfiles").tokenInput(globalUrls.profiles.search.getCommonProfiles, {
        queryParam: "profile",
        propertyToSearch: "profile",
        theme: "facebook",
        tokenValue: "Profile_ID",
        limit: 999,
        preventDuplicates: false
    });

});

$("#modal-cloudConfig-deploy").on("click", "#deployCloudConfig", function(){
    let profileIds = mapObjToSignleDimension($("#deployCloudConfigProfiles").tokenInput("get"), "profile");
    let hostId = $("#deployCloudConfigHosts").find(":selected").parents("optgroup").attr("id");
    let project = $("#deployCloudConfigHosts").find(":selected").val()

    let containerNameInput = $("#modal-cloudConfig-deploy input[name=containerName]");
    let containerName = containerNameInput.val();
    let profileNameInput = $("#modal-cloudConfig-deploy input[name=profileName]");
    let profileName = profileNameInput.val();

    if(containerName == ""){
        makeToastr(JSON.stringify({state: "error", message: "Please provide instance name"}));
        containerNameInput.focus()
        return false;
    } else if(!$.isNumeric(hostId)){
        makeToastr(JSON.stringify({state: "error", message: "Please choose a destination"}));
        $("#deployCloudConfigHosts").focus();
        return false;
    }

    $("#modal-cloudConfig-confirm").modal("show");
    return false;
});
</script>
<?php
    require_once __DIR__ . "/confirmDeploy.php";
?>
