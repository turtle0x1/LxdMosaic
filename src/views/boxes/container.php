<div id="containerBox" class="boxSlide">
    <div class="col-md-12 text-center">
        <h4> <u>
            <span id="container-currentState"></span>
            <span id="container-containerNameDisplay"></span>
            <span id="container-imageDescription"></span>
        </u></h4>
    </div>
    <div class="row" id="containerViewBtns">
        <div class="col-md-6 text-center">
            <div class="card bg-primary card-hover-primary text-center toggleCard" id="goToDetails">
                <div class="card-body">
                    Details
                </div>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <div class="card card-hover-primary text-center toggleCard" id="goToConsole">
                <div class="card-body">
                    Console
                </div>
            </div>
        </div>
    </div>
<div id="containerDetails">
<div class="row">

    <div class="col-md-6">
        <div class="card text-white bg-deepblue">
          <div class="card-body">
              <h5> <u> Container Details </u> </h5>
              Host: <span id="container-hostNameDisplay"></span>
              <br/>
              <a
                  href="https://github.com/lxc/pylxd/issues/242#issuecomment-323272318"
                  target="_blank">
                  CPU Time:
              </a><span id="container-cpuTime"></span>
              <br/>
              Created: <span id="container-createdAt"></span>
          </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white bg-primary">
          <div class="card-body">
            <h5> <u> Network Information </u> </h5>
                <div class="col-md-12" id="networkDetails">
                </div>

          </div>
        </div>
    </div>

</div>
<br/>
<div class="row">
<!-- <h4> Container: <`span id="containerName"></span> </h4> -->
<div class="col-md-3">
    <div class="card card-accent-danger">
      <div class="card-header" role="tab" id="container-actionsHeading">
        <h5>
          <a data-toggle="collapse" data-parent="#accordion" href="#actionsCollapse" aria-expanded="true" aria-controls="container-actionsCollapse">
            Actions
          </a>
        </h5>
      </div>

      <div id="actionsCollapse" class="collapsed show" aria-expanded="true" role="tabpanel" aria-labelledby="container-actionsHeading">
        <div class="card-block">
            <div class="form-group">
                <label><u> Change State </u></label>
                <select class="form-control" id="container-changeState">
                    <option value="" selected="selected">  </option>
                    <option value="startContainer"> Start </option>
                    <option value="stopContainer"> Stop </option>
                    <option value="restartContainer"> Restart </option>
                    <option value="freezeContainer"> Freeze </option>
                    <option value="unfreezeContainer"> Unfreeze </option>
                </select>
            </div>
            <hr/>
            <button class="btn btn-block btn-primary editContainerSettings">
                Settings
            </button>
            <button class="btn btn-block btn-success takeSnapshot">
                Snapshot
            </button>
            <hr/>
            <button class="btn btn-block btn-info copyContainer">
                Copy
            </button>
            <button class="btn btn-block btn-primary migrateContainer">
                Migrate
            </button>
            <button class="btn btn-block btn-warning renameContainer">
                Rename
            </button>
            <button class="btn btn-block btn-danger deleteContainer">
                Delete
            </button>
        </div>
      </div>
    </div>
</div>
<div class="col-md-3">
    <div class="card border-primary">
      <div class="card-header" role="tab">
        <h5>
            Profiles
        </h5>
      </div>
      <div class="collapse show" role="tabpanel" aria-labelledby="headingOne">
        <div class="card-block table-responsive">
            <table class="table table-bordered"id="profileData">
                  <thead class="thead-inverse">
                      <tr>
                          <th> Name </th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
            </table>
        </div>
      </div>
    </div>
    <br/>
    <div class="card border-primary">
      <div class="card-header" role="tab">
        <h5>
            Snapshots
        </h5>
      </div>
      <div class="collapse show" role="tabpanel" aria-labelledby="headingOne">
        <div class="card-block table-responsive">
            <table class="table table-bordered"id="snapshotData">
                  <thead class="thead-inverse">
                      <tr>
                          <th> Name </th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
            </table>
        </div>
      </div>
    </div>
</div>
<div class="col-md-6">
      <div class="card card-accent-success">
        <div class="card-header" role="tab" id="headingOne">
          <h5>
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              Memory Details
            </a>
          </h5>
        </div>

        <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
          <div class="card-block">
              <div class="row table-responsive">
              <table class="table" id="memoryData">
                    <thead class="thead-inverse">
                        <tr>
                            <th> Family </th>
                            <th> Ussage </th>
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
</div>
<div id="containerConsole">
    <div id="terminal-container"></div>
</div>
</div>
<script src="/assets/xterm/xterm.js"></script>
<script src="/assets/xterm/addons/attach.js"></script>
<script>

var term = new Terminal();
var consoleSocket;
var currentTerminalProcessId;

function loadContainerViewAfter(data = null, milSeconds = 2000)
{
    setTimeout(function(){
        let p = currentContainerDetails;
        if($.isPlainObject(data)){
            p = data;
        }
        loadContainerView(p);
    }, 2000);
}

function loadContainerTreeAfter(milSeconds = 2000)
{
    setTimeout(function(){
        createContainerTree();
    }, milSeconds);
}

function loadContainerView(data)
{
    $("#containerConsole").hide();
    $("#containerDetails").show();
    $("#goToDetails").trigger("click");
    if(consoleSocket !== undefined){
        consoleSocket.emit("close", currentTerminalProcessId);
    }

    ajaxRequest(globalUrls.containers.getDetails, data, function(result){
        let x = $.parseJSON(result);

        if(x.state == "error"){
            makeToastr(result);
            return false;
        }

        addBreadcrumbs([data.host, "container", data.container ], ["", "", "active"]);

        let disableActions = x.state.status_code !== 102;

        $(".renameContainer").attr("disabled", disableActions);
        $(".deleteContainer").attr("disabled", disableActions);

        $("#container-currentState").html(`<i class="` +statusCodeIconMap[x["state"]["status_code"]] +`"></i>`);
        $("#container-changeState").val("");

        //NOTE Read more here https://github.com/lxc/pylxd/issues/242
        let containerCpuTime = nanoSecondsToHourMinutes(x["state"]["cpu"].usage);

        let os = x.details.config.hasOwnProperty("image.os") ? x.details.config["image.os"] : "<b style='color: #ffc107'>Can't find OS</b>";
        let version  = x.details.config.hasOwnProperty("image.version") ? x.details.config["image.version"] : "<b style='color: #ffc107'>Cant find verison</b>";

        $("#container-hostNameDisplay").text(data.host);
        $("#container-containerNameDisplay").text(data.container);
        $("#container-imageDescription").html(" - " + os + " " + "(" + version + ")");
        $("#container-cpuTime").text(containerCpuTime);
        $("#container-createdAt").text(moment(x.details.create_at).format("MMM DD YYYY h:mm A"));

        let snapshotTrHtml = "";

        if(x["snapshots"].length == 0){
            snapshotTrHtml = "<tr><td colspan='999' class='text-center'> No snapshots </td></tr>"
        }else{
            $.each(x["snapshots"], function(i, item){
                snapshotTrHtml += "<tr><td><a href='#' id='" + item + "' class='viewSnapsnot'>" + item + "</a></td></tr>";
            });
        }

        $("#snapshotData >  tbody").empty().append(snapshotTrHtml);

        let profileTrHtml = "";

        if(x.details["profiles"].length == 0){
            profileTrHtml = "<tr><td colspan='999' class='text-center'> No Profiles </td></tr>"
        }else{
            $.each(x.details["profiles"], function(i, item){
                profileTrHtml += "<tr><td><a href='#' class='toProfile'>" + item + "</a></td></tr>";
            });
        }

        $("#profileData >  tbody").empty().append(profileTrHtml);

        let networkData = "";

        $.each(x["state"]["network"],  function(i, item){
            if(i == "lo"){
                return;
            }
            networkData += "<div class='padding-bottom: 2em;'><b>" + i + ":</b><br/>";
            let lastKey = item.addresses.length - 1;
            $.each(item.addresses, function(i, item){
                networkData +=  "<span style='padding-left:3em'>" + item.address + "<br/></span>";
            });
            networkData += "</div>";
        });
        $("#networkDetails").empty().append(networkData);

        let memoryHtml = "";

        $.each(x["state"]["memory"], function(i, item){
            memoryHtml += "<tr><td>" + i + "</td><td>" + formatBytes(item) + "</tr>";
        });

        $("#memoryData > tbody").empty().append(memoryHtml);

        $(".boxSlide").hide();
        $("#containerBox").show();
    });
}

$("#containerBox").on("click", ".renameContainer", function(){
    $("#modal-container-rename").modal("show");
});

$("#containerBox").on("click", ".toggleCard", function(){
    $("#containerViewBtns").find(".bg-primary").removeClass("bg-primary");
    $(this).addClass("bg-primary");
});


$("#containerBox").on("click", "#goToDetails", function(){
    $("#containerDetails").show();
    $("#containerConsole").hide();
});

$("#containerBox").on("click", "#goToConsole", function(){
    Terminal.applyAddon(attach);

    $("#containerDetails").hide();
    $("#containerConsole").show();

    const terminalContainer = document.getElementById('terminal-container');
      // Clean terminal
      while (terminalContainer.children.length) {
        terminalContainer.removeChild(terminalContainer.children[0]);
      }
      term = new Terminal({});
      window.term = term;  // Expose `term` to window for debugging purposes

      term.open(terminalContainer);

      // fit is called within a setTimeout, cols and rows need this.
      setTimeout(() => {
        $.ajax({
          type: "POST",
          url: '/terminals?cols=' + term.cols + '&rows=' + term.rows,
          data: {},
          success: function(processId) {
              currentTerminalProcessId = processId;
              consoleSocket = io.connect("/terminals", {query: $.extend({
                  pid: processId,
              }, currentContainerDetails)
              });
              consoleSocket.on('data', function(data) {
                   term.write(data);
              });

              // Browser -> Backend
              term.on('data', function(data) {
                consoleSocket.emit('data', data);
              });
              // consoleSocket = new WebSocket(consoleSocketURL);
              consoleSocket.onopen = function() {
                  term.attach(consoleSocket);
                  term._initialized = true;
              };

          },
          dataType: "json"
        });
      }, 0);
});

$("#containerBox").on("click", ".toProfile", function(){
    let profile = $(this).text();
    loadProfileView(profile, currentContainerDetails.host, function(){
        viewProfile(profile, currentContainerDetails.host);
    });

});

$("#containerBox").on("click", ".copyContainer", function(){
    $("#modal-container-copy").modal("show");
});

$("#containerBox").on("click", ".migrateContainer", function(){
    $("#modal-container-migrate").modal("show");
});

$("#containerBox").on("click", ".takeSnapshot", function(){
    $("#modal-container-snapshot").modal("show");
});

$("#containerBox").on("click", ".editContainerSettings", function(){
    $("#modal-container-editSettings").modal("show");
});

$("#containerBox").on("click", ".deleteContainer", function(){
    $.confirm({
        title: 'Delete Container ' + currentContainerDetails.host + '/' + currentContainerDetails.container,
        content: 'Are you sure you want to delete this container ?!',
        buttons: {
            cancel: function () {},
            delete: {
                btnClass: 'btn-danger',
                action: function () {
                    ajaxRequest(globalUrls["containers"].delete, currentContainerDetails, function(data){
                        let r = makeToastr(data);
                        if(r.state == "success"){
                            loadContainerTreeAfter();
                        }
                        $("#overviewBox").show();
                        $("#containerBox").hide();
                    });
                }
            }
        }
    });
});

$("#containerBox").on("change", "#container-changeState", function(){
    let url = globalUrls["containers"]["state"][$(this).val()];
    ajaxRequest(url, currentContainerDetails, function(data){
        let result = makeToastr(data);
        loadContainerTreeAfter();
        loadContainerViewAfter();
    });
});

$("#containerBox").on("click", ".viewSnapsnot", function(){
    snapshotDetails.snapshotName = $(this).attr("id");
    $("#modal-container-restoreSnapshot").modal("show");
});
</script>
<?php
    require __DIR__ . "/../modals/containers/migrateContainer.php";
    require __DIR__ . "/../modals/containers/takeSnapshot.php";
    require __DIR__ . "/../modals/containers/restoreSnapshot.php";
    require __DIR__ . "/../modals/containers/copyContainer.php";
    require __DIR__ . "/../modals/containers/renameContainer.php";
    require __DIR__ . "/../modals/containers/createContainer.php";
    require __DIR__ . "/../modals/containers/editSettings.php";
?>
