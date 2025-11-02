<!-- Modal -->
<div class="modal fade" id="modal-vms-create" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create VM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="min-height: 80vh; max-height: 80vh;">
                <div class="row">
                    <div class="col-md-3">
                        <ul class="list-group" id="createVmStepList">
                            <li style="cursor: pointer" class="list-group-item active">1. Basic Details</li>
                            <li style="cursor: pointer" class="list-group-item">2. Config (Optional)</li>
                        </ul>
                    </div>
                    <div class="col-md-9" style="max-height: 77vh; min-height: 77vh; overflow-y: scroll; border-left: 1px solid black;">
                        <div class="createVmBox" data-step="1" id="">
                            <div class="mb-2">
                                <b> Name </b>
                                <input class="form-control" name="name" />
                            </div>
                            <div class="mb-2">
                                <b> Hosts </b>
                                <input class="form-control" name="hosts" id="newVmHosts" />
                            </div>
                            <div class="mb-2">
                                <b> Memory Limit (1GB Default) </b>
                                <input class="form-control" name="memoryLimit" value="1GB" />
                            </div>
                            <b> Source Image </b>
                            <div class="card border-dark mb-2">
                                <div class="card-header vmImageSrcToggle cursor-pointer">
                                    <h4><input type="radio" data-type="image" class="me-2" />Image<h4>
                                </div>
                                <div class="card-body collapse">
                                    <div class="mb-2">
                                        <b>Image</b>
                                        <input id="newVirtualMachineImage" type="text" class="form-control" />
                                        <small class="text-muted text-sm">
                                            <i class="fas fa-info-circle text-info me-2"></i>Only searchs your LXD servers, you may need to import an image from a remote first!
                                        </small>
                                    </div>
                                    <div class="mb-2">
                                        <b> Username </b>
                                        <input class="form-control" name="username" />
                                        <small class="text-muted text-sm">
                                            <i class="fas fa-info-circle text-info me-2"></i>Your account password will be set to <code>ubuntu</code>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="card  border-dark">
                                <div class="card-header vmImageSrcToggle cursor-pointer">
                                    <h4><input type="radio" data-type="iso" class="me-2" />ISO File<h4>
                                </div>
                                <div class="card-body collapse">
                                    <div class="mb-2">
                                        <b>File</b>
                                        <div class="dropzone" id="vmIsoUpload"></div>
                                    </div>
                                    <div class="mb-2">
                                        <b class="d-block"> Pool </b>
                                        <span id="createVmIsoPoolWarning">Choose host/s first!</span>
                                        <select class="form-select" name="isoPool" id="createVmIsoPoolSelect" style="display: none"></select>
                                    </div>
                                    <div class="mb-2">
                                        <b> OS Name </b>
                                        <input class="form-control" name="isoName" value="" />
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="createVmSecureBoot" checked>
                                        <label class="form-check-label" for="createVmSecureBoot">Secure Boot (Often should be disabled)</label>
                                    </div>
                                    <small class="text-muted text-sm">
                                        <i class="fas fa-info-circle text-info me-2"></i>After installation detach the ISO by removing the profile attached to the instance.
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="createVmBox" data-step="2" id="" style="display: none;">
                            <div>
                                <label for="newVmSettings">
                                    Settings(Optional)
                                </label>
                                <button id="addVmSetting" class="btn btn-sm btn-outline-primary float-end"><i class="fas fa-plus"></i></button>
                            </div>
                            <table class="table table-borered" id="newVmSettings">
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary createVirtualMachine" data-start="0">Create</button>
                <button type="button" class="btn btn-success createVirtualMachine" data-start="1">Create & Start</button>
            </div>
        </div>
    </div>
</div>
<script>
    var vmSettingRow = "";
    var _vmDropzone = null;

    function _updateCreateVmStoragePool() {
        const hosts = mapObjToSignleDimension($("#newVmHosts").tokenInput("get"), "hostId");
        if (hosts.length == 0) {
            $("#createVmIsoPoolWarning").show()
            $("#createVmIsoPoolSelect").hide()
            return false
        }
        $("#createVmIsoPoolWarning").hide()
        $("#createVmIsoPoolSelect").show()
        $("#createVmIsoPoolSelect").attr("disabled", false)
        ajaxRequest('/api/storage/pools/common', {
            hosts
        }, (data) => {
            data = makeToastr(data)
            if (data.length == 0) {
                $("#createVmIsoPoolSelect").empty().append(`<option>No Common Pools Use 1 Host</option>`).attr("disabled", true);
                return false;
            }
            const poolOptions = `<option value="">Please select</option>` + data.map(pool => {
                return `<option value="${pool}">${pool}</option>`
            }).join("")
            $("#createVmIsoPoolSelect").empty().append(poolOptions)
        })
    }

    $("#newVirtualMachineImage").tokenInput(globalUrls.images.search.searchAllHosts, {
        queryParam: "search",
        tokenLimit: 1,
        propertyToSearch: "description",
        theme: "facebook",
        tokenValue: "details",
        setExtraSearchParams: () => {
            return {
                type: "virtual-machine"
            };
        }
    });

    $("#newVmHosts").tokenInput(globalUrls.hosts.search.search, {
        queryParam: "hostSearch",
        propertyToSearch: "host",
        tokenValue: "hostId",
        preventDuplicates: false,
        theme: "facebook",
        onAdd: _updateCreateVmStoragePool,
        onDelete: _updateCreateVmStoragePool
    });

    $("#modal-vms-create").on("show.bs.modal", function() {
        $("#createVmIsoPoolWarning").show()
        $("#createVmIsoPoolSelect").hide()
        $(".vmImageSrcToggle").find("input[type=radio]").prop("checked", false)
        $("#createVmSecureBoot").prop("checked", true)
        $(".vmImageSrcToggle").parents(".card").find(".card-body").addClass("collapse")
        if (_vmDropzone == null) {
            _vmDropzone = new Dropzone("#vmIsoUpload", {
                url: '/',
                maxFilesize: 20480, // max file size in MB (20GB)
                acceptedFiles: ".iso",
                maxFiles: 1,
                autoProcessQueue: false,
                init: function() {
                    this.on("maxfilesexceeded", function(file) {
                        this.removeAllFiles();
                        this.addFile(file); // Replace previous file if a new one is added
                    });
                }
            });
        }

        ajaxRequest(globalUrls.instances.settings.getAllAvailableSettings, {}, function(data) {
            data = makeToastr(data);
            let selectHtml = "<select name='key' class='form-select containerSetting'><option value=''>Please Select</option>";
            $.each(data, function(i, item) {
                selectHtml += `<option value='${item.key}' data-value="${item.value}">${item.key}</option>`;
            });
            selectHtml += `</select>`;
            vmSettingRow = `<tr>
                <td>${selectHtml}</td>
                <td><input name="value" class='form-control'/></td>
                <td><button class="removeSetting btn btn-danger"><i class='fas fa-trash'></i></button></td>
            </tr>`
        });
    });

    $("#modal-vms-create").on("hide.bs.modal", function() {
        $("#modal-vms-create input").val("");
        $("#modal-vms-create input[name=memoryLimit]").val("1GB");
        $("#newVmHosts").tokenInput("clear");
        $("#newVirtualMachineImage").tokenInput("clear");
        $("#newVmSettings > tbody").empty()
    });

    $("#modal-vms-create").on("change", "#createVmSecureBoot", function() {
        if ($(this).is(":checked")) {
            $(".secureBootRow").remove()
            return false;
        }
        $("#newVmSettings > tbody").append(`
            <tr class="secureBootRow">
                <td><select name='key' class='form-select containerSetting'><option value='security.secureboot'>security.secureboot</option></td>
                <td><input name="value" class='form-control' disabled value="false"/></td>
                <td><button class="removeSetting btn btn-danger disabled"><i class='fas fa-trash'></i></button></td>
            </tr>
            <tr class="secureBootRow">
                <td><select name='key' class='form-select containerSetting'><option value='security.csm'>security.csm</option></td>
                <td><input name="value" class='form-control' disabled value="true"/></td>
                <td><button class="removeSetting btn btn-danger disabled"><i class='fas fa-trash'></i></button></td>
            </tr>
            
        `)
    })

    $("#modal-vms-create").on("click", ".vmImageSrcToggle", function() {
        $(".vmImageSrcToggle").find("input[type=radio]").prop("checked", false)
        $(".vmImageSrcToggle").parents(".card").find(".card-body").addClass("collapse")
        $(this).find("input[type=radio]").prop("checked", true)
        $(this).parents(".card").find(".card-body").removeClass("collapse")
    })

    $("#modal-vms-create").on("click", "#createVmStepList li", function() {
        changeCreateVmBox($(this).index() + 1);
    });

    $("#modal-vms-create").on("click", ".removeSetting", function() {
        $(this).parents("tr").remove();
    });

    $("#modal-vms-create").on("click", "#addVmSetting", function() {
        $("#newVmSettings > tbody").append(vmSettingRow);
    });

    function changeCreateVmBox(newIndex) {
        $(".createVmBox").hide();
        $(`.createVmBox[data-step='${(newIndex)}']`).show();
        $("#createVmStepList").find(".active").removeClass("active");
        $("#createVmStepList").find(`li:eq(${newIndex - 1})`).addClass("active");
    }

    $("#modal-vms-create").on("click", ".createVirtualMachine", function() {
        let hosts = mapObjToSignleDimension($("#newVmHosts").tokenInput("get"), "hostId");



        let nameInput = $("#modal-vms-create input[name=name]");
        let name = nameInput.val();


        let btn = $(this);

        let startVm = parseInt(btn.data("start"));

        let defaultBtnText = "Create";

        if (startVm == 1) {
            defaultBtnText = "Create & Start";
        }



        if (name == "") {
            changeCreateVmBox(1)
            nameInput.focus();
            makeToastr(JSON.stringify({
                state: "error",
                message: "Please input vm name"
            }));
            return false;
        } else if (hosts.length == 0) {
            changeCreateVmBox(1)
            $("#newNetworkHosts").focus();
            makeToastr(JSON.stringify({
                state: "error",
                message: "Please select atleast one host"
            }));
            return false;
        }

        const imageType = $(".vmImageSrcToggle >> input[type=radio]:checked").data("type");
        let imageSettings = {}
        let username = ""
        let isoFile = null;
        if (imageType == null) {
            changeCreateVmBox(1)
            makeToastr(JSON.stringify({
                state: "error",
                message: "Please choose source"
            }));
            return false;
        } else if (imageType == "image") {
            let usernameInput = $("#modal-vms-create input[name=username]");
            username = usernameInput.val();
            let image = $("#newVirtualMachineImage").tokenInput("get");
            if (image.length == 0 || !image[0].hasOwnProperty("details")) {
                changeCreateVmBox(1)
                makeToastr(JSON.stringify({
                    state: "error",
                    message: "Please select image"
                }));
                return false;
            } else if (username == "") {
                changeCreateVmBox(1)
                usernameInput.focus();
                makeToastr(JSON.stringify({
                    state: "error",
                    message: "Please input username"
                }));
                return false;
            }
            imageSettings = image[0].details
        } else if (imageType == "iso") {
            let isoNameInput = $("#modal-vms-create input[name=isoName]");
            let isoName = isoNameInput.val().trim()
            let poolInput = $("#createVmIsoPoolSelect");
            pool = poolInput.val();
            let image = $("#newVirtualMachineImage").tokenInput("get");
            let files = _vmDropzone.getAcceptedFiles();
            if (files.length === 0) {
                alert("Please select a file first.");
                return;
            } else if (pool == "") {
                changeCreateVmBox(1)
                poolInput.focus();
                makeToastr(JSON.stringify({
                    state: "error",
                    message: "Please select pool"
                }));
                return false;
            } else if (isoName == "") {
                changeCreateVmBox(1)
                isoNameInput.focus();
                makeToastr(JSON.stringify({
                    state: "error",
                    message: "Please enter iso name"
                }));
                return false;
            }
            isoFile = files[0];
            imageSettings = {
                isoName,
                pool
            }
        }
        imageSettings.imageType = imageType

        let memoryLimitInput = $("#modal-vms-create input[name=memoryLimit]");
        let memoryLimit = memoryLimitInput.val();

        btn.html('<i class="fa fa-cog fa-spin"></i>Creating..');
        $("#modal-vms-create").find(".btn").attr("disabled", true);

        let config = {};
        let invalid = false;
        let message = "";
        let failedInput;
        $("#newVmSettings > tbody > tr").each(function() {
            let keyInput = $(this).find("select[name=key]");
            let valueInput = $(this).find("input[name=value]");
            let key = keyInput.val();
            let value = valueInput.val();
            if (key == "") {
                failedInput = keyInput
                invalid = true;
                message = "Please select setting";
                return false;
            } else if (value == "") {
                failedInput = valueInput
                invalid = true;
                message = "Please select value";
                return false;
            }

            config[key] = value;
        });

        if (invalid) {
            changeCreateVmBox(2)
            $("#modal-vms-create").find(".btn").attr("disabled", false);
            btn.html(defaultBtnText);
            failedInput.focus()
            makeToastr(JSON.stringify({
                state: "error",
                message: message + " or delete row"
            }));
            return false;
        }

        config["limits.memory"] = memoryLimit



        let formData = new FormData();


        // Add other data if needed
        formData.append('name', name);
        formData.append('start', startVm);
        formData.append('username', username);
        hosts.forEach(hostId => {
            formData.append('hostIds[]', hostId);
        });
        
        for (let key in imageSettings) {
            if (imageSettings.hasOwnProperty(key)) {
                formData.append(`imageDetails[${key}]`, imageSettings[key]);
            }
        }

        for (let key in config) {
            if (config.hasOwnProperty(key)) {
                formData.append(`config[${key}]`, config[key]);
            }
        }
        formData.append('file', isoFile);

        ajaxRequest(globalUrls.instances.virtualMachines.create, formData, (data) => {
            data = makeToastr(data);
            $("#modal-vms-create").find(".btn").attr("disabled", false);
            if (data.state == "success") {
                $("#modal-vms-create").modal("hide");
            }
            btn.html(defaultBtnText);
        });
    });
</script>