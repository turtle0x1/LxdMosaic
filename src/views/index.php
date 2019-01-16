<?php
$haveServers = $this->container->make("dhope0000\LXDClient\Model\Hosts\HostList");

if ($haveServers->haveAny() !== true) {
    header("Location: /views/firstRun");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LXD Client</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script
    			  src="https://code.jquery.com/jquery-3.3.1.min.js"
    			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    			  crossorigin="anonymous"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

    <!-- Global CSS  -->
    <link rel="stylesheet" href="/assets/styles.css">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js" integrity="sha256-L3S3EDEk31HcLA5C6T2ovHvOcD80+fgqaCDt2BAi92o=" crossorigin="anonymous"></script>

    <!-- jqueryConfirm assets -->
    <link rel="stylesheet" href="/assets/jqueryConfirm/dist/jquery-confirm.min.css">
    <script src="/assets/jqueryConfirm/dist/jquery-confirm.min.js"></script>

    <!-- Ace web editor  -->
    <script src="/assets/ace/ace.js" type="text/javascript" charset="utf-8"></script>


    <link rel="stylesheet" href="/assets/toastr.js/toastr.min.css">
    <script src="/assets/toastr.js/toastr.min.js"></script>


    <link rel="stylesheet" href="/assets/bootstrap-treeview/bootstrap-treeview.min.css">
    <script src="/assets/bootstrap-treeview/bootstrap-treeview.min.js"></script>

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <!-- Data tables  -->


    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap.min.js"></script>

    <script src="/assets/token/src/jquery.tokeninput.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/token/styles/token-input.css" />
    <link rel="stylesheet" type="text/css" href="/assets/token/styles/token-input-facebook.css" />


    <script src="/socket.io/socket.io.js"></script>

    <script>
        var currentContainerDetails = null;

        var currentProfileDetails = {
            profile: null,
            host: null
        };

        var globalUrls = {
            profiles: {
                search:{
                    getCommonProfiles: "/api/Profiles/Search/SearchProfiles/getAllCommonProfiles"
                },
                getAllProfiles: '/api/Profiles/GetAllProfilesController/getAllProfiles',
                delete: '/api/Profiles/DeleteProfileController/delete',
                rename: '/api/Profiles/RenameProfileController/rename',
            },
            containers:{
                create: "/api/Containers/CreateController/create",
                delete: "/api/Containers/DeleteContainerController/deleteContainer",
                getDetails: "/api/Containers/GetContainerDetailsController/get",
                getCurrentSettings: "/api/Containers/GetCurrentContainerSettingsController/get",
                migrate: "/api/Containers/MigrateContainerController/migrateContainer",
                copy: "/api/Containers/CopyContainerController/copyContainer",
                rename: "/api/Containers/RenameContainerController/renameContainer",
                setSettings: "/api/Containers/SetSettingsController/set",
                state:{
                    startContainer: "/api/Containers/StateController/startContainer",
                    stopContainer: "/api/Containers/StateController/stopContainer",
                    restartContainer: "/api/Containers/StateController/restartContainer",
                    freezeContainer: "/api/Containers/StateController/freezeContainer",
                    unfreezeContainer: "/api/Containers/StateController/unfreezeContainer",
                },
                snapShots: {
                    take: "/api/Containers/Snapshot/TakeSnapshotController/takeSnapshot",
                    delete: "/api/Containers/Snapshot/DeleteSnapshotController/deleteSnapshot",
                    restore: "/api/Containers/Snapshot/RestoreSnapshotController/restoreSnapshot",
                    createFrom: "/api/Containers/CopyContainerController/copyContainer",
                }
            },
            hosts: {
                search: {
                    search: "/api/Hosts/SearchHosts/search"
                },
                containers: {
                    getAll: "/api/Hosts/Containers/GetAllController/getAll",
                },
                getAllHosts: "/api/Hosts/GetHostsController/getAllHosts",
                getOverview: "/api/Hosts/GetOverviewController/get"
            },
            images: {
                search: {
                    searchAllHosts: "/api/Images/Search/SearchController/getAllAvailableImages",
                },
                getLinuxContainersOrgImages: "/api/Images/GetLinuxContainersOrgImagesController/get",
                delete: "/api/Images/DeleteImagesController/delete",
                getAll: "/api/Images/GetImagesController/getAllHostImages",
                import: "/api/Images/ImportLinuxContainersByAliasController/import",
            },
            cloudConfig: {
                create: '/api/CloudConfig/CreateController/create',
                update: '/api/CloudConfig/UpdateController/update',
                delete: '/api/CloudConfig/DeleteController/delete',
                deploy: '/api/CloudConfig/DeployController/deploy',
                getAll: '/api/CloudConfig/GetAllController/getAll',
                getDetails: '/api/CloudConfig/GetDetailsController/get',
                getAllFiles: '/api/CloudConfig/GetAllCloudConfigController/getAllConfigs'
            },
            user: {
                setHostProject: '/api/User/SetSessionHostProjectController/set'
            }
        };

        function mapObjToSignleDimension(obj, keyToMap)
        {
            let output = [];
            Object.keys(obj).map(function(key, index) {
               output.push(obj[key][keyToMap]);
            });
            return output;
        }
    </script>
</head>
<body>
    <nav class="navbar   navbar-toggleable-md navbar-light bg-faded">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">LXD Manager</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active overview">
                    <a class="nav-link" href="#">Overview</a>
                </li>
                <li class="nav-item viewProfiles">
                    <a class="nav-link" href="#">Profiles</a>
                </li>
                <li class="nav-item viewCloudConfigFiles">
                    <a class="nav-link" href="#">Cloud Config</a>
                </li>
                <li class="nav-item viewImages">
                    <a class="nav-link" href="#">Images</a>
                </li>

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="btn btn-primary pull-right" id="addNewServer">
                    <a> Add A Server </a>
                </li>
            </ul>
        </div>
    </nav>
        <div class="container-fluid">
        <div class="row">
        <div class="col-md-3">
            <div class="" id="jsTreeSidebar">
            </div>
        </div>
        <div class="col-md-7" id="boxHolder">
            <?php
                require __DIR__ . "/boxes/overview.php";
                require __DIR__ . "/boxes/container.php";
                require __DIR__ . "/boxes/profile.php";
                require __DIR__ . "/boxes/cloudConfig.php";
                require __DIR__ . "/boxes/images.php";
            ?>
        </div>
        <div class="col-md-2">
            <div class="tree well" id="">
            <b> Operations </b>
            <div id="operationsList"></div>
            <!-- <div class="alert alert-info">
                Operations are a bit vague in lxd versions preceding
                lxd version 3.0, as they lack a description. This means
                that programs would have to track every initiated operation to
                know what it was doing.
                <br/>
                Which isn't ideal, because it would
                be nice to show cli initiated operations. Also LXD doesn't make
                available completed operations so its hard to tell if it failed
                so this will show running tasks, but as to there completion
                status i wouldn't hold hope.
            </div> -->
            </div>
        </div>
        </div>
        </div>
    </body>

    <script type='text/javascript'>

var profileData = null;


// var currentContainerListObj = null;

var statusCodeMap = {
    100: "OperationCreated",
    101: "Started",
    102: "Stopped",
    103: "Running",
    104: "Cancelling",
    105: "Pending",
    106: "Starting",
    107: "Stopping",
    108: "Aborting",
    109: "Freezing",
    110: "Frozen",
    111: "Thawed",
    112: "Error",
    200: "Success",
    400: "Failure",
    40: "Cancelled",
}
// TODO Sort these out
var statusCodeIconMap = {
    100: "fa fa-ban",
    101: "fa fa-play",
    102: "fa fa-stop-circle",
    103: "fa fa-play",
    104: "fa fa-ban",
    105: "fa fa-clock",
    106: "fa fa-play",
    107: "fa fa-stop",
    108: "Aborting",
    109: "Freezing",
    110: "fa fa-snowflake-o",
    111: "Thawed",
    112: "fa fa-exclamation-triangle",
    200: "fa fa-check",
    400: "fa fa-exclamation-triangle",
    40:  "Cancelled",
}

toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": true,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": true,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}

var socket = io();

socket.on('operationUpdate', function(msg){
   let id = msg.metadata.id;
   let icon = statusCodeIconMap[msg.metadata.status_code];
   let description = msg.metadata.description;
   let host = msg.host;
   let hostList = $("#operationsList").find("[data-host='" + host + "']");

   if(hostList.length == 0){
       $("#operationsList").append("<div data-host='" + host + "'>"+
            "<div class='text-center'><h5><u>" + host + "</u></h5></div>"+
            "<div class='opList'></div></div>"
        );
   }

   let hostOpList = hostList.find(".opList");

   let liItem = hostOpList.find("#" + id);

   if(liItem.length > 0){
       liItem.html("<span class='" + icon + "'></span>" + description);
   }else{
       hostOpList.prepend(makeOperationHtmlItem(id, icon, description));
   }
});

function makeOperationHtmlItem(id, icon, description)
{
    return "<div id='" + id + "'><span class='" + icon + "'></span>" + description + "</div>";
}

var editor = ace.edit("editor");
  editor.setTheme("ace/theme/monokai");
  editor.getSession().setMode("ace/mode/yaml");

$(document).on("keyup", ".validateName", function(){
    this.value = this.value.replace(/[^-a-zA-Z0-9]+/g,'');
})

function makeToastr(response) {
    let x = $.parseJSON(response);
    toastr[x.state](x.message);
    if(x.hasOwnProperty("lxdResponse")){
        let response = x.lxdResponse;
    }
    return x;
}


$(function(){
    createContainerTree();
    loadServerOview();
});

var unknownServerDetails = {
    cpu: {
        sockets: [{vendor: "Unknown Vendor"}],
        total: "Unknown Cpu Total"
    },
    memory: {
        used: "Uknown Memory Use",
        total: "Uknown Memory Total"
    }
};

function loadServerOview()
{
    ajaxRequest(globalUrls["hosts"].getOverview, null, function(data){
        let x = $.parseJSON(data);
        if(x.hasOwnProperty("error")){
            makeToastr(data);
            return false;
        }
        let html = "";
        $.each(x, function(host, data){
            let memoryUsed = unknownServerDetails.memory.used;
            let memoryTotal = unknownServerDetails.memory.total;

            if(data.hasOwnProperty("memory")){
                memoryUsed = formatBytes(data.memory.used);
                memoryTotal = formatBytes(data.memory.total);
            }

            if(!data.hasOwnProperty("cpu")){
                data = unknownServerDetails;
            }

            html += "<h5><u>" + host + "</u></h5>" +
                "<dl class='row'>" +
                "<dt class='col-sm-4'> CPU </dt> "+
                    "<dd class='col-sm-8'>" +
                        data.cpu.sockets[0].vendor +
                        " (" + data.cpu.total + " threads)" +
                    "</dd>" +
                "<dt class='col-sm-4'> Memory (used / free) </dt>" +
                    "<dd class='col-sm-8'>" +
                        memoryUsed + " / " +
                        memoryTotal +
                    "</dd>";

                if(data.extensions.supportsProjects){
                    html += "<dt class='col-sm-4'>Current Project</dt>"+
                    "<dd><select class='changeHostProject form-control'>";
                    $.each(data.projects, function(o, project){
                        let selected = project == data.currentProject ? "selected" : "";
                        html += "<option data-host='" + data.hostId  + "' "+
                            " value='" + project + "' " + selected + ">"
                            + project + "</option>"
                    });
                    html += "</select></dd>";
                }

                html += "</dl>";
        });
        $(".boxSlide").hide();
        $("#overviewBox").show();
        $("#serverOverviewDetails").empty().html(html);
    });
}

function createContainerTree(){
    ajaxRequest(globalUrls.hosts.containers.getAll, {}, (data)=>{
        data = $.parseJSON(data);
        let treeData = [];
        $.each(data, function(i, host){
            let containers = [];
            $.each(host.containers, function(containerName, details){
                containers.push({
                    text: containerName,
                    icon: statusCodeIconMap[details.state.status_code],
                    type: "container",
                    host: i
                });
            });
            treeData.push({
                text: i,
                nodes: containers,
                type: "server",
                icon: "fa fa-server"
            })
        });
        $('#jsTreeSidebar').treeview({
            data: treeData,         // data is not optional
            levels: 5,
            onNodeSelected: function(event, node) {
                if(node.type == "container"){
                    setContDetsByTreeItem(node);
                    loadContainerView(currentContainerDetails);
                } else if(node.type == "server"){
                    loadServerOview();
                }
            }
        });
    });

}

function formatBytes(bytes,decimals) {
   if(bytes == 0) return '0 Bytes';
   var k = 1024,
       dm = decimals || 2,
       sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
       i = Math.floor(Math.log(bytes) / Math.log(k));
   return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function setContDetsByTreeItem(node)
{
    // currentContainerListObj =  node;
    currentContainerDetails = {
        container: node.text,
        host: node.host
    }
    return currentContainerDetails;
}
// Adapted from https://stackoverflow.com/questions/4687723/how-to-convert-minutes-to-hours-minutes-and-add-various-time-values-together-usi
function convertMinsToHrsMins(mins) {
  let h = Math.floor(mins / 60);
  let m = mins % 60;
  h = h < 10 ? '0' + h : h;
  m = m < 10 ? '0' + m : m;
  m = parseFloat(m).toFixed(0);
  return `${h}:${m}`
}

function nanoSecondsToHourMinutes(nanoseconds) {
    return convertMinsToHrsMins(nanoseconds / 60000000000);
}


function nl2br (str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

$(document).on("click", "#addNewServer", function(){
    $("#modal-hosts-add").modal("show");
});

$(document).on("change", ".changeHostProject", function(){
    let selected = $(this).find(":selected");

    let x = {
        hostId: selected.data("host"),
        project: selected.val()
    };

    ajaxRequest(globalUrls.user.setHostProject, x, function(data){
        createContainerTree();
    })

});

$(document).on("click", ".overview", function(){
    changeActiveNav(".overview");
    createContainerTree();
    loadServerOview();
    $(".boxSlide").hide();
    $("#overviewBox").show();
});

$(document).on("click", ".viewProfiles", function(){
    loadProfileView();
});

$(document).on("click", ".viewCloudConfigFiles", function(){
    changeActiveNav(".viewCloudConfigFiles");
    loadCloudConfigTree();
});


function changeActiveNav(newActiveSelector)
{
    $(".navbar").find(".active").removeClass("active");
    $(".navbar").find(newActiveSelector).addClass("active");
}

function createTableRowsHtml(data, childPropertyToSearch)
{
    let html = "";
    $.each(data, function(x, y){
        if($.isPlainObject(y)){
            html += "<tr><td class='text-center' colspan='2'>" + x + "</td></tr>";
            if(typeof childPropertyToSearch == "string"){
                $.each(y[childPropertyToSearch], function(i, p){
                    html += "<tr><td>" + i + "</td><td>" + nl2br(y) + "</td></tr>";
                });
            }else{
                $.each(y, function(i, p){
                    html += "<tr><td>" + i + "</td><td>" + nl2br(p) + "</td></tr>";
                });
            }
        }else{
            html += "<tr><td>" + x + "</td><td>" + nl2br(y) + "</td></tr>";
        }
    });
    return html;
}


function ajaxRequest(url, data, callback){
    $.ajax({
         type: 'POST',
         data: data,
         url: url,
         success: function(data){
             callback(data);
         }
     });
}
</script>
<?php
    require_once __DIR__ . "/modals/hosts/addHosts.php";
    require_once __DIR__ . "/modals/images/import.php";
?>
</html>
