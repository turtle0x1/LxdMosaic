<?php
require "../../vendor/autoload.php";

$haveServers = $this->container->make("dhope0000\LXDClient\Model\Hosts\HostList");

if ($haveServers->haveAny() === true) {
    header("Location: /");
    exit;
}
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title>LXD Mosaic</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

<!-- jqueryConfirm assets -->
<link rel="stylesheet" href="/assets/jqueryConfirm/dist/jquery-confirm.min.css">
<script src="/assets/jqueryConfirm/dist/jquery-confirm.min.js"></script>


<!-- Toastr  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha256-ENFZrbVzylNbgnXx0n3I1g//2WeO47XxoPe0vkp3NC8=" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha256-3blsJd4Hli/7wCQ+bmgXfOdK7p/ZUMtPXY08jmxSSgk=" crossorigin="anonymous"></script>

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
</head>
<html>
<body>
    <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class='text-center'><u> LXD Mosaic </u></h1>

            <h4 class=''> Add Your Hosts
                <button class="btn btn-sm btn-primary" id="addServer">
                    <i class="fa fa-plus"></i>
                </button>
            </h4>
            <div class="alert alert-info">
                If a host is in a cluster LXDMosaic will find other cluster memebers
                and try to import them with the same trust password!
            </div>
            <div id="serverGroups"></div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-primary" id="addServers"> Done </button>
                </div>
            </div>
        </div>

    </div>
    </div>
</body>
</html>
<script>

let inputTemplate = `<div class="input-group mb-3 serverGroup">
    <div class="input-group-addon">
        <label> Server </label>
    </div>
    <input placeholder="ip / hostname" name="connectDetails" class="form-control"/>
    <input placeholder="trust password" name="trustPassword" type="password" class="form-control"/>
    <input placeholder="Alias (Optional)" name="alias" type="text" class="form-control"/>
    <div class="input-group-addon">
        <button class="btn btn-danger removeRow" type="button">
            <i class="fa fa-trash"></i>
        </button>
    </div>
</div>`;


$(function(){
    $("#serverGroups").empty().append(inputTemplate);
});

$(document).on("click", ".removeRow", function(){
    $(this).parents(".serverGroup").remove();
});

$(document).on("click", "#addServer", function(){
    $("#serverGroups").append(inputTemplate);
});

$(document).on("click", "#addServers", function(){
    let serverGroups = $(".serverGroup");
    let details = {
        hostsDetails: []
    };
    serverGroups.map(function(){

        let connectDetailsInput = $(this).find("input[name=connectDetails]");
        let trustPasswordInput = $(this).find("input[name=trustPassword]");
        let connectDetailsInputVal = connectDetailsInput.val();
        let trustPasswordInputVal = trustPasswordInput.val();
        if(connectDetailsInputVal == ""){
            connectDetailsInput.focus();
            toastr["error"]("Please provide connection details");
            return false;
        } else if(trustPasswordInputVal == ""){
            trustPasswordInput.focus();
            toastr["error"]("Please provide trust provide");
            return false;
        }

        let alias = $(this).find("input[name=alias]").val();
        alias = alias == "" ? null : alias;

        details.hostsDetails.push({
            name: connectDetailsInputVal,
            trustPassword: trustPasswordInputVal,
            alias: alias
        });
    });

    $.ajax({
         type: 'POST',
         data: details,
         url: "/api/Hosts/AddHostsController/add",
         success: function(data){
             let result = $.parseJSON(data);
             if(result.state !== "success"){
                 toastr["error"](result.message);
                 return false;
             }
             location.reload();
         }
     });
});

</script>
