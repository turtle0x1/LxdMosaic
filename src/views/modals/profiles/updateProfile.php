<div class="modal fade" id="modal-profile-update" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="exampleModalLongTitle">Update Profile <b><span class="updateProfileModal-profileName"></span></b></h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-2" id="selectProfileFormGroup">
                                <b> Select Profile </b>
                                <select class="form-select" id="profileSelect"></select>
                            </div>
                        </div>
                    </div>
                    <div id="profileUpdateData">
                        <div class="row">
                            <div class="col-sm-8 col-md-8 col-lg-8">
                                <b>Select Servers To Update</b>
                                <div class="mb-2">
                                    <input type="search" class="form-control" id="severSearch" placeholder="Search Servers">
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-8" id="profileHostsBox"></div>
                        <div class="row col-md-8">
                            <div class="col-md-12 col-lg-12">
                                <b> Select settings To Update </b>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th> Key </th>
                                            <th> Value </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th> Key </th>
                                            <th> Value </th>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary copy">Update</button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#modal-profile-update").on("shown.bs.modal", function() {
        if (!$.isPlainObject(currentProfileDetails) || typeof currentProfileDetails.profile !== "string") {
            $("#profileUpdateData").hide();
            ajaxRequest(globalUrls.profiles.getAllProfiles, null, function(data) {
                var data = makeToastr(data);
                let html = "<option value=''>Please Select </option>";
                $.each(data, function(host, profiles) {
                    $.each(profiles.profiles, function(name, data) {
                        html += `<option value='${name}'>${name}</option>`;
                    });
                })
                $("#profileSelect").empty().append(html);
            });
        } else {
            $("#selectProfileFormGroup").hide();
            $("#profileUpdateData").show();
        }
    });

    $('#severSearch').on('keyup', function() {
        var pattern = $(this).val();
        $('#profileHostsBox .items').hide();
        $('#profileHostsBox .items').filter(function() {
            return $(this).text().match(new RegExp(pattern, 'i'));
        }).show();
    });

    $("#modal-profile-update").on("change", "#profileSelect", function() {
        let x = {
            profile: $(this).val()
        };

        if (x.profile == "") {
            $("#profileUpdateData").hide();
            return false;
        }

        ajaxRequest(globalUrls.profiles.search.getCommonProfiles, x, function(data) {
            let hosts = makeToastr(data);
            let checkboxHtml = '';
            $.each(hosts, function(i, host) {
                checkboxHtml += `<div class="items col-xs-5 col-sm-5 col-md-3 col-lg-3">
                    <div class="info-block block-info clearfix">
                        <div class="square-box pull-left">
                            <span class="fa fa-tags"></span>
                        </div>
                        <div data-bs-toggle="buttons" class="btn-group bizmoduleselect">
                            <label class="btn btn-default">
                                <div class="bizcontent">
                                    <input type="checkbox" name="hosts[]" autocomplete="off" value="${host}">
                                    <span class="fa fa-server"></span>
                                    <h5>${host.replace("https://", "")}</h5>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>`;
            });

            $("#profileHostsBox").empty().append(checkboxHtml);
            $("#profileUpdateData").show();
        });
    });
</script>