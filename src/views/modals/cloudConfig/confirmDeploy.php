    <!-- Modal -->
<div class="modal fade" id="modal-cloudConfig-confirm">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Cloud-Config Log Results</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-2">
            <label> Shell For <code>Cloud-Config</code> Results </label>
            <input class="form-control" name="shell" value="bash" />
            <small class="form-text d-block text-muted"><i class="fas fa-info-circle me-1 text-info"></i><code>bash</code> for Ubuntu!</small>
            <small class="form-text d-block text-muted"><i class="fas fa-info-circle me-1 text-info"></i><code>ash</code> for Alpine!</small>
            <small class="form-text d-block text-muted"><i class="fas fa-exclamation-triangle me-1 text-warning"></i><code>empty</code> for no output!</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="confirm">Deploy</button>
      </div>
    </div>
  </div>
</div>
<script>
$("#modal-cloudConfig-confirm").on("click", "#confirm", function(){
    let btn = $(this);

    btn.attr("disabled", true);
    btn.html(`<i class="fas fa-cog fa-spin me-2"></i>Deploying...`)

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
        btn.attr("disabled", false);
        btn.html(`Deploy`)
        return false;
    } else if(!$.isNumeric(hostId)){
        makeToastr(JSON.stringify({state: "error", message: "Please choose a destination"}));
        $("#deployCloudConfigHosts").focus();
        btn.attr("disabled", false);
        btn.html(`Deploy`)
        return false;
    }

    let gpus = $("#deployContainerGpu").val();

    let x = {
        hosts: [hostId],
        containerName: containerName,
        cloudConfigId: deployCloudConfigObj.cloudConfigId,
        profileName: profileName,
        additionalProfiles: profileIds,
        gpus: gpus,
        project: project
    };

    let shell = $("#modal-cloudConfig-confirm input[name=shell]").val()

    ajaxRequest(globalUrls.cloudConfig.deploy, x, (response)=>{
        response = makeToastr(response);
        btn.attr("disabled", false);
        btn.html(`Deploy`)
        if(response.state == "error"){
            return false;
        }

        $("#modal-cloudConfig-confirm").modal("hide")

        if(shell == ""){
            $("#modal-cloudConfig-deploy").modal("hide");
            return false;
        }

        $("#modal-cloudConfig-deploy .modal-body").empty().append('<div id="deploy-terminal-container"></div>')

        $("#modal-cloudConfig-deploy").find(".modal-dialog").addClass("modal-xl");
        $("#modal-cloudConfig-deploy .modal-body").empty().append('<div style="min-height: 80vh;" id="deploy-terminal-container"></div>')
        $("#modal-cloudConfig-deploy .modal-footer").hide();

        const terminalContainer = document.getElementById('deploy-terminal-container');
        // Clean terminal
        while (terminalContainer.children.length) {
            terminalContainer.removeChild(terminalContainer.children[0]);
        }

        term = new Terminal({});
        term.loadAddon(fitAddon)

        term.open(terminalContainer);
        fitAddon.fit()

        // Theoretically no need to inject credentials
        // here as auth is only called when a socket
        // is first connected (in this case when the
        // operations socket is setup - which will
        // always come before this) but to be safe ...
        consoleSocket = new WebSocket(`wss://${getQueryVar("host", window.location.hostname)}:${getQueryVar("port", 443)}/node/cloudConfig?${$.param($.extend({
            ws_token: userDetails.apiToken,
            user_id: userDetails.userId,
            shell: shell,
            userId: userDetails.userId,
            hostId: hostId,
            instance: containerName,
            project: project
        }, currentContainerDetails))}`);

        term.loadAddon(new window.AttachAddon.AttachAddon(consoleSocket));
        fitAddon.fit()
    });
});


</script>
