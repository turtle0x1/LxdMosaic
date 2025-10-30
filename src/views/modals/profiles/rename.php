<div class="modal fade" id="modal-profile-rename" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Rename Profile <b><span class="renameProfile-profileName"></span></b></h5>
                    <div><i class="fas fa-server me-1"></i></b><span id="renameProfile-host"></span></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <b> New Name </b>
                    <input class="form-control" id="newProfileName" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="renameProfile">Rename</button>
            </div>
        </div>
    </div>
</div>
<script>
    var renameProfileData = {
        hostAlias: null,
        currentName: null,
        hostId: null
    }

    $("#modal-profile-rename").on("hide.bs.modal", function() {
        $("#modal-profile-rename input").val("");
    });

    $("#modal-profile-rename").on("shown.bs.modal", function() {
        if (renameProfileData.hostAlias == null) {
            makeToastr(JSON.stringify({
                state: "error",
                message: "Developer fail - Please provide host alias"
            }));
            return false;
        } else if (renameProfileData.currentName == null) {
            makeToastr(JSON.stringify({
                state: "error",
                message: "Developer fail - Please provide currentName"
            }));
            return false;
        }

        $(".renameProfile-profileName").text(renameProfileData.currentName);
        $("#renameProfile-host").text(renameProfileData.hostAlias);
    });

    $("#modal-profile-rename").on("click", "#renameProfile", function() {
        let newProfileNameInput = $("#modal-profile-rename #newProfileName");
        let newProfileNameVal = newProfileNameInput.val();

        if (newProfileNameVal == "") {
            makeToastr(JSON.stringify({
                state: "error",
                message: "Please provide new profile name"
            }));
            newProfileNameInput.focus();
            return false;
        }

        let x = $.extend({
            newProfileName: newProfileNameVal
        }, renameProfileData);
        ajaxRequest(globalUrls.profiles.rename, x, (data) => {
            data = makeToastr(data);
            if (data.state == "success") {
                $("#modal-profile-rename").modal("toggle");
                router.navigate(`/profiles/${hostIdOrAliasForUrl(renameProfileData.hostId, renameProfileData.hostAlias)}/${newProfileNameVal}`)
            }
        });
    });
</script>