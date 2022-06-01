<?php

use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

require "../../vendor/autoload.php";

$haveServers = $this->container->make("dhope0000\LXDClient\Model\Hosts\HostList"); /** @phpstan-ignore-line */

if ($haveServers->haveAny() === true) {
    header("Location: /");
    exit;
}

$timezoneList = (new dhope0000\LXDClient\Tools\Utilities\DateTools())->getTimezoneList();
$currenTimezone = date_default_timezone_get();

$socketPath = "/var/snap/lxd/common/lxd/unix.socket";
$addServer = false;

if (file_exists($socketPath)) {
    // Attempt to hit the socket, if were in SNAP we should be automatically be
    // able to access the host
    $socket = '/var/snap/lxd/common/lxd/unix.socket';
    set_time_limit(0);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_UNIX_SOCKET_PATH, $socket);
    curl_setopt($ch, CURLOPT_BUFFERSIZE, 256);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1000000);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, "http:/localhost/1.0/resources");
    $x = curl_exec($ch);
    $addServer = $x !== false;
}

?>
<!DOCTYPE html>
<head>
<link rel="apple-touch-icon" sizes="57x57" href="/assets/lxdMosaic/favicons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/assets/lxdMosaic/favicons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/assets/lxdMosaic/favicons/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/assets/lxdMosaic/favicons/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/assets/lxdMosaic/favicons/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/assets/lxdMosaic/favicons/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/assets/lxdMosaic/favicons/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/assets/lxdMosaic/favicons/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/assets/lxdMosaic/favicons/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/assets/lxdMosaic/favicons/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/lxdMosaic/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/assets/lxdMosaic/favicons/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/lxdMosaic/favicons/favicon-16x16.png">
<link rel="manifest" href="/assets/lxdMosaic/favicons/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/assets/lxdMosaic/favicons/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<meta charset="utf-8">
<title>LXDMosaic | First Run</title>
<link rel="stylesheet" href="/assets/dist/login.dist.css">
<script src="/assets/dist/login.dist.js"></script>
<link rel="stylesheet" href="/assets/dist/external.fontawesome.css">
<style>
body {
    font-size: 2rem;
}

.jconfirm, .list-group-item, label, .text-sm {
    font-size: 1rem;
}

#getStartedBtn, #footer {
    opacity: 0;
}

.slide {
    opacity: 0;
    display: none;
}

.introText {
    opacity: 0;
    text-align: center;
}

.progress-bar {
    -webkit-transition: none !important;
    transition: none !important;
}

.start-33 {
    left: 33.3% !important;
}
.start-66 {
    left: 66.6% !important;
}

@media (min-width: 992px) {
    .text-sm {
        font-size: 1.5rem;
    }

    body {
        font-size: 3rem;
    }
}
</style>

</head>
<html>
<body>
    <div class="container-fluid ">
    <div class="row " style="min-height: 90vh;">
        <div class="col-sm-12 col-lg-6 offset-lg-3 slide">
            <div class="row" style="height: 20vh">
                <div class="col-md-12 m-0">
                </div>
            </div>
            <div style="min-height: 60vh; max-height: 60vh;" class=" " id="test">
                <img  src="/assets/lxdMosaic/logo.png" class="mx-auto introText d-block" alt="" width="140" height="140" id="siteLogo">
                <p id="welcomeHeading" class="mt-5 introText">Welcome to LXDMosaic</p>
                <p id="welcomeText" class="mt-5 introText text-sm">Follow along and fill out some basic information to get started</p>
            </div>
            <div style="height: 10vh">
                <button class="btn btn-primary float-end text-sm getStarted" id="getStartedBtn">Get Started<i class="fas fa-chevron-right ms-2"></i></button>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6 offset-lg-3 slide">
            <div id="test3" class="introText">
                <div class="row" style="height: 20vh">
                    <div class="col-md-12 m-0">
                        <p><i class="fas fa-server me-2"></i>Add Hosts</p>
                        <p class="mt-2 text-sm">First lets set up your hosts, you can always add more later</p>
                    </div>
                </div>
                <div class="list-group  ms-auto me-auto text-start" style=" min-height: 60vh; max-height: 60vh; overflow: auto;" id="serverGroups">
                    <?php if ($addServer) { ?>
                    <a href="#" class="list-group-item list-group-item-action newHost" data-alias="localhost" data-name="" data-trust-password="" data-socket-path="<?= $socketPath ?>">
                       <div class="d-flex w-100 justify-content-between">
                         <h5 class="mb-1"><i style="width: 24px; height: 24px; text-align: center;" class="fas fa-server me-2"></i>localhost</h5>
                         <small><button class="btn btn-sm btn-outline-secondary deleteHost"><i class="fas fa-trash"></i></button></small>
                       </div>
                       <p class="mb-1"><i style="width: 24px; height: 24px; text-align: center;" class="fas fa-file me-2"></i><?= $socketPath ?></p>
                       <small><i style="width: 24px; height: 24px; text-align: center;" class="fas fa-info-circle text-success me-2"></i>Detected host accessible via socket.</small>
                     </a>
                     <?php } ?>
                 </div>
                 <div style="height: 10vh">
                     <button class="btn btn-outline-secondary float-start text-sm" id="addServer">Add Host<i class="fas fa-plus ms-2"></i></button>
                     <button class="btn btn-primary text-center float-end text-sm setupUsers">Setup Admin<i class="fas fa-chevron-right ms-2"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6 offset-lg-3 slide">
            <div id="test4" class="introText">
                <div class="row" style="height: 20vh">
                    <div class="col-md-12 m-0">
                        <p><i class="fas fa-users me-2"></i>Admin Account</p>
                        <p class="mt-2 text-sm">Now lets set up an admin account, you can add more users later.</p>
                    </div>
                </div>
                <div id="userGroups" class=" ms-auto me-auto text-start mt-auto" style=" min-height: 60vh; max-height: 60vh; overflow: auto;">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" value="admin" disabled>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" id="adminPasswordInput" class="form-control" autocomplete="new-password">
                            </div>
                          </div>
                     </div>
                 </div>
                 <div style="height: 10vh">
                     <button class="btn btn-primary text-center float-end text-sm setupSettings">Settings<i class="fas fa-chevron-right ms-2"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6 offset-lg-3 slide">
            <div id="test5" class="introText">
                <div class="row" style="height: 20vh">
                    <div class="col-md-12 m-0">
                        <p><i class="fas fa-cog me-2"></i>Site Settings</p>
                        <p class="mt-2 text-sm">Finally lets configure some settings for LXDMosaic, you can change these later.</p>
                    </div>
                </div>
                <div class="" style=" min-height: 60vh; max-height: 60vh; overflow: auto;">
                <div class="form-floating">
                  <select class="form-select instanceSetting" data-setting-id="<?= InstanceSettingsKeys::TIMEZONE; ?>" id="instanceSetting<?= InstanceSettingsKeys::TIMEZONE; ?>">
                    <?php
                        foreach ($timezoneList as $timezone) {
                            $s = $timezone === $currenTimezone ? "selected" : '';
                            echo "<option value='{$timezone}' ". $s . ">{$timezone}</option>";
                        }
                    ?>
                  </select>
                  <label for="instanceSetting<?= InstanceSettingsKeys::TIMEZONE; ?>">LXDMosaic Timezone</label>
                </div>
                <div class="form-floating mt-3">
                  <select class="form-select instanceSetting" data-setting-id="<?= InstanceSettingsKeys::RECORD_ACTIONS; ?>" id="instanceSetting<?= InstanceSettingsKeys::RECORD_ACTIONS; ?>">
                    <option value="0" selected>No</option>
                    <option value="1">Yes</option>
                  </select>
                  <label for="instanceSetting<?= InstanceSettingsKeys::RECORD_ACTIONS; ?>">Keep Audit Log Of All Users Actions?</label>
                </div>
                <div class="form-floating mt-3 mb-3">
                    <input value="LXDMosaic"  class="form-control instanceSetting" data-setting-id="<?= InstanceSettingsKeys::SITE_TITLE; ?>" id="instanceSetting<?= InstanceSettingsKeys::SITE_TITLE; ?>">
                    <label for="instanceSetting<?= InstanceSettingsKeys::SITE_TITLE; ?>">Site Title</label>
                </div>
                <div class="form-floating mt-3">
                  <select class="form-select instanceSetting" data-setting-id="<?= InstanceSettingsKeys::STRONG_PASSWORD_POLICY; ?>" id="instanceSetting<?= InstanceSettingsKeys::STRONG_PASSWORD_POLICY; ?>">
                    <option value="0">No</option>
                    <option value="1" selected>Yes</option>
                  </select>
                  <label for="instanceSetting<?= InstanceSettingsKeys::STRONG_PASSWORD_POLICY; ?>">Strong Password Policy?</label>
                </div>
                <div class="form-floating mt-3">
                  <select class="form-select instanceSetting" data-setting-id="<?= InstanceSettingsKeys::BACKUP_HISTORY; ?>" id="instanceSetting<?= InstanceSettingsKeys::BACKUP_HISTORY; ?>">
                    <option value="-1 month">1 Month</option>
                    <option value="-3 month">3 Months</option>
                    <option value="-6 month">6 Months</option>
                    <option value="-1 year">1 Year</option>
                    <option value="-3 year">3 Years</option>
                    <option value="-5 year">5 Years</option>
                    <option value="-999 year">999 Years</option>
                  </select>
                  <label for="instanceSetting<?= InstanceSettingsKeys::BACKUP_HISTORY; ?>">How long keep backup records?</label>
                </div>
                <div class="form-floating mt-3">
                  <select class="form-select instanceSetting" data-setting-id="<?= InstanceSettingsKeys::PROJECT_ANALYTICS_STORAGE; ?>" id="instanceSetting<?= InstanceSettingsKeys::PROJECT_ANALYTICS_STORAGE; ?>">
                    <option value="-1 month">1 Month</option>
                    <option value="-3 month">3 Months</option>
                    <option value="-6 month">6 Months</option>
                    <option value="-1 year">1 Year</option>
                    <option value="-3 year">3 Years</option>
                    <option value="-5 year">5 Years</option>
                    <option value="-999 year">999 Years</option>
                  </select>
                  <label for="instanceSetting<?= InstanceSettingsKeys::PROJECT_ANALYTICS_STORAGE; ?>">How long keep projects metric history?</label>
                </div>
                <div class="form-floating mt-3">
                  <select class="form-select instanceSetting" data-setting-id="<?= InstanceSettingsKeys::INSTANCE_METRIC_HISTORY; ?>" id="instanceSetting<?= InstanceSettingsKeys::INSTANCE_METRIC_HISTORY; ?>">
                    <option value="-1 month">1 Month</option>
                    <option value="-3 month">3 Months</option>
                    <option value="-6 month">6 Months</option>
                    <option value="-1 year">1 Year</option>
                    <option value="-3 year">3 Years</option>
                    <option value="-5 year">5 Years</option>
                    <option value="-999 year">999 Years</option>
                  </select>
                  <label for="instanceSetting<?= InstanceSettingsKeys::INSTANCE_METRIC_HISTORY; ?>">How long keep instance metric history?</label>
                </div>
                </div>
                <div style="height: 10vh">
                    <button class="btn btn-success text-center float-end text-sm" id="launchLxdMosaic">Launch<i class="fas fa-rocket ms-2"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="row " style="min-height: 10vh;" id="footer">
        <div class="col-sm-12 col-lg-6 offset-lg-3">
            <div class="position-relative m-4">
              <div class="progress" style="height: 1px;">
                <div id="firstRunProgressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <button type="button" class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-primary rounded-pill viewSplash" style="width: 2.2rem; height:2.2rem;"><i class="fas fa-info"></i></button>
              <button type="button" class="position-absolute top-0 start-33 translate-middle btn btn-sm btn-secondary rounded-pill getStarted" style="width: 2.2rem; height:2.2rem;"><i class="fas fa-server"></i></button>
              <button type="button" class="position-absolute top-0 start-66 translate-middle btn btn-sm btn-secondary rounded-pill setupUsers" style="width: 2.2rem; height:2.2rem;"><i class="fas fa-user"></i></button>
              <button type="button" class="position-absolute top-0 start-100 translate-middle btn btn-sm btn-secondary rounded-pill setupSettings" style="width: 2.2rem; height:2.2rem;"><i class="fas fa-cog"></i></button>
            </div>
        </div>
    </div>
    </div>
</body>
</html>
<script>

$(function(){
    $(".slide:eq(0)").show();
    $(".slide:eq(0)").css({opacity: 1});
    $("#siteLogo").delay(0).animate({opacity: 1}, 2000);
    $("#welcomeHeading").delay(1000).animate({opacity: 1}, 2500);
    $("#welcomeText").delay(2500).animate({opacity: 1}, 2500);
    $("#footer").delay(4000).animate({opacity: 1}, 2000);
    $("#getStartedBtn").delay(6000).animate({opacity: 1}, 2000);

})

$("#footer").on("click", ".rounded-pill", function(){
    $(".rounded-pill:gt(" + ($(this).index() - 1) + ")").removeClass("btn-primary").addClass("btn-secondary")
    $(".rounded-pill:lt(" + ($(this).index() - 1) + ")").removeClass("btn-secondary").addClass("btn-primary")
});

$(document).on("click", ".viewSplash", function(){
    $( ".slide:visible" ).fadeOut( "slow", function() {
          $(".slide:eq(0)").show().css({opacity: 1})
          $(".progress-bar").animate({width: "0%"}, 1000, function(){
            $("#footer").find(".btn:eq(0)").addClass("btn-primary").removeClass("btn-secondary")
          })
    });
});

$(document).on("click", ".getStarted", function(){
    $( ".slide:visible" ).fadeOut( "slow", function() {
          $(".slide:eq(1)").show().css({opacity: 1})
          $("#test3").delay(0).animate({opacity: 1}, 1500);
          $(".progress-bar").animate({width: "33.3%"}, 1000, function(){
            $("#footer").find(".btn:eq(1)").addClass("btn-primary").removeClass("btn-secondary")
          })
      })
});

$(document).on("click", ".setupUsers", function(){
    $( ".slide:visible" ).fadeOut( "slow", function() {

          $(".slide:eq(2)").show().css({opacity: 1})
          $("#test4").delay(0).animate({opacity: 1}, 1500);
          $(".progress-bar").animate({width: "66.6%"}, 1000, function(){
            $("#footer").find(".btn:eq(2)").addClass("btn-primary").removeClass("btn-secondary")
          })
      })
});
$(document).on("click", ".setupSettings", function(){
    $( ".slide:visible" ).fadeOut( "slow", function() {
          $(".slide:eq(3)").show().css({opacity: 1})
          $("#test5").delay(0).animate({opacity: 1}, 1500);
          $(".progress-bar").animate({width: "100%"}, 1000, function(){
            $("#footer").find(".btn:eq(3)").addClass("btn-primary").removeClass("btn-secondary")
          })
      })
});

let userTemplate = `<div class="card mb-2">
    <div class="card-body userGroup">
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input type="text" placeholder="username" name="username" class="form-control">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="fas fa-key"></i></span>
            <input type="password" placeholder="password" name="password" class="form-control">
        </div>
       <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="flexSwitchCheckCheckedDisabled">
        <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Admin</label>
      </div>
      </div>
 </div>

`;

$(document).on("click", ".deleteHost", function(){
    $(this).parents(".newHost").remove();
});

$(document).on("click", "#addServer", function(){
    $.confirm({
        icon: "fa fa-server",
        title: "Add Host",
        content: `<div class="mb-2">
            <label>Alias</label>
            <input class="form-control" name="alias" placeholder="PC, Laptop, Server-1"/>
        </div>
        <label class="mb-2">How Do You Want To Access The Host?</label>
        <nav>
          <div class="nav nav-pills mb-3" id="host-nav" role="tablist">
            <button class="nav-link w-50 active" id="nav-network-tab" data-bs-toggle="tab" data-bs-target="#nav-network" type="button" role="tab" aria-controls="nav-network" aria-selected="true">Trust Password</button>
            <button class="nav-link w-50" id="nav-socket-tab" data-bs-toggle="tab" data-bs-target="#nav-socket" type="button" role="tab" aria-controls="nav-socket" aria-selected="false">Socket</button>
          </div>
        </nav>
        <div class="tab-content" id="host-navContent">
          <div class="tab-pane fade show active" id="nav-network" role="tabpanel" aria-labelledby="nav-network-tab">
              <div class="mb-2">
                  <label>IP Address / Hostname</label>
                  <input class="form-control" name="name" placeholder=""/>
              </div>
              <div class="mb-2">
                  <label>Trust Password</label>
                  <input class="form-control" type="password" name="trustPassword" placeholder=""/>
              </div>
          </div>
          <div class="tab-pane fade" id="nav-socket" role="tabpanel" aria-labelledby="nav-socket-tab">
              <div class="mb-2">
                  <label>Socket Path</label>
                  <input class="form-control" name="socketPath" value="/var/snap/lxd/common/lxd/unix.socket"/>
              </div>
          </div>
        </div>
        `,
        buttons: {
            cancel: {},
            add: {
                btnClass: "btn-primary",
                action: function(){
                    let alias = this.$content.find('input[name=alias]').val().trim();

                    if(alias === ""){
                        $.alert("Please enter an alias")
                        return false;
                    }

                    let name = ""
                    let trustPassword = ""
                    let socketPath = ""

                    let activeNav = this.$content.find("#host-nav .active").data().bsTarget
                    let connectionDetailsHtml = "";

                    if(activeNav === "#nav-socket"){
                        socketPath = this.$content.find('input[name=socketPath]').val().trim();

                        if(socketPath === ""){
                            $.alert("Please enter socket path")
                            return false;
                        }

                        connectionDetailsHtml = `<p class="mb-1"><i style="width: 24px; height: 24px; text-align: center;" class="fas fa-file me-2"></i>${socketPath}</p>`
                    }else{
                        name = this.$content.find('input[name=name]').val().trim();
                        trustPassword = this.$content.find('input[name=trustPassword]').val().trim();
                        if(name === ""){
                            $.alert("Please enter ip address / hostname")
                            return false;
                        }else if(trustPassword === ""){
                            $.alert("Please enter trust password")
                            return false;
                        }

                        connectionDetailsHtml += `<p class="mb-1"><i style="width: 24px; height: 24px; text-align: center;" class="fas fa-globe me-2"></i>${name}</p>
                        <p class="mb-1"><i style="width: 24px; height: 24px; text-align: center;" class="fas fa-key me-2"></i>${trustPassword}</p>`
                    }

                    $("#serverGroups").append(`<a href="#" class="list-group-item list-group-item-action newHost" data-alias="${alias}" data-name="${name}" data-trust-password="${trustPassword}" data-socket-path="${socketPath}">
                       <div class="d-flex w-100 justify-content-between">
                         <h5 class="mb-1"><i style="width: 24px; height: 24px; text-align: center;" class="fas fa-server me-2"></i>${alias}</h5>
                         <small><button class="btn btn-sm btn-outline-secondary deleteHost"><i class="fas fa-trash"></i></button></small>
                       </div>
                       ${connectionDetailsHtml}
                     </a>`)
                }
            }
        }
    });
});

$(document).on("click", "#launchLxdMosaic", function(){
    let launchBtn = $(this);

    launchBtn.attr("disabled", true)

    let adminPasswordInput = $("#adminPasswordInput");

    if(adminPasswordInput.length === 0){
        toastr["error"]("Nice try hax0r!");
        launchBtn.attr("disabled", false)
        return false;
    }

    let adminPassword = adminPasswordInput.val();

    if(adminPassword === ""){
        $("#footer").find(".setupUsers:eq(0)").trigger("click")
        adminPasswordInput.addClass("is-invalid")
        toastr["error"]("Please provide an admin password!");
        launchBtn.attr("disabled", false)
        return false;
    }
    adminPasswordInput.removeClass("is-invalid").addClass("is-valid")

    if($(".newHost").length == 0){
        $("#footer").find(".getStarted:eq(0)").trigger("click")
        toastr["error"]("Please provide atleast one host");
        launchBtn.attr("disabled", false)
        return false;
    }

    let details = {
        hosts: [],
        adminPassword: adminPassword,
        settings: []
    };

    failed = false;

    $(".newHost").each(function(){
        details.hosts.push($(this).data());
    });

    if(failed){
        launchBtn.attr("disabled", false)
        return false;
    }

    failed = false;
    settings = [];
    $(".instanceSetting").each(function(){
        let input = $(this);
        let value = input.val()
        if(value == ""){
            input.addClass("is-invalid");
            failed = true;
            launchBtn.attr("disabled", false)
            return false;
        }
        let data = input.data()
        data.value = value
        details.settings.push(data)
        input.removeClass("is-invalid").addClass("is-valid disabled").attr("disabled", true)

    });

    if(failed){
        launchBtn.attr("disabled", false)
        return false;
    }

    $.ajax({
         type: 'POST',
         data: details,
         headers: {
             userId: 1
         },
         url: "/api/InstanceSettings/FirstRunController/run",
         success: function(data){
             let result = $.parseJSON(data);
             if(result.state !== "success"){
                 launchBtn.attr("disabled", false)
                 if(result.message.match(/Password too short/g)){
                     $("#footer").find(".setupUsers:eq(0)").trigger("click")
                     adminPasswordInput.addClass("is-invalid")
                 }else if(result.message.match(/Can't connect to/g)){
                     $("#footer").find(".getStarted:eq(0)").trigger("click")
                 }
                 toastr["error"](result.message);
                 return false;
             }
             location.reload();
         }
     });
});

</script>
