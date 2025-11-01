<?php

$expectedGet = ["hostId", "project", "instance"];

foreach ($expectedGet as $expected) {
    if (!isset($_GET[$expected]) || empty($_GET[$expected])) {
        throw new \Exception("Missing $expected", 1);
    }
}

$hostId = $_GET["hostId"];
$project = $_GET["project"];
$instance = $_GET["instance"];

$userSession = $this->container->make("dhope0000\LXDClient\Tools\User\UserSession"); /** @phpstan-ignore-line */
$validatePermissions = $this->container->make("dhope0000\LXDClient\Tools\User\ValidatePermissions"); /** @phpstan-ignore-line */

$userId = $userSession->getUserId();
$apiToken = $userSession->getToken();

$validatePermissions->canAccessHostProjectOrThrow($userId, $hostId, $project);

echo "<script>
var userDetails = {
    apiToken: '$apiToken',
    userId: $userId
}

</script>";


?>
<!DOCTYPE html>
<html lang="en">
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

      <script src="/assets/dist/external.dist.js" type="text/javascript" charset="utf-8"></script>

      <!-- Main styles for this application-->
      <link href="/assets/dist/external.dist.css" rel="stylesheet">

      <link rel="stylesheet" href="/assets/lxdMosaic/styles.css">

      <base href="./">
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <title>LXD Mosaic</title>

      <link rel="stylesheet" href="/assets/dist/external.fontawesome.css">

      <script src="/assets/lxdMosaic/globalFunctions.js"></script>
      <script src="/assets/lxdMosaic/globalDetails.js"></script>
      <script src="/assets/lxdMosaic/operationSockets.js"></script>
  </head>
  <body class="app">
      <!-- ES2015/ES6 modules polyfill -->
      <script type="module">
          window._spice_has_module_support = true;
      </script>
      <script>
          window.addEventListener("load", function() {
              if (window._spice_has_module_support) return;
              var loader = document.createElement("script");
              loader.src = "/assets/spiceHtml5/src/thirdparty/browser-es-module-loader/dist/browser-es-module-loader.js";
              document.head.appendChild(loader);
          });
      </script>

      <style>
      .spice-screen
      {
          min-height: 600px;
          height: 100%;
          margin: 10px;
          padding: 0;
      }
      </style>

      <script type="module" crossorigin="anonymous">
          import * as SpiceHtml5 from './assets/spiceHtml5/src/main.js';

          var host = null, port = null;
          var sc;

          function spice_set_cookie(name, value, days) {
              var date, expires;
              date = new Date();
              date.setTime(date.getTime() + (days*24*60*60*1000));
              expires = "; expires=" + date.toGMTString();
              document.cookie = name + "=" + value + expires + "; path=/";
          };

          function spice_query_var(name, defvalue) {
              var match = RegExp('[?&]' + name + '=([^&]*)')
                                .exec(window.location.search);
              return match ?
                  decodeURIComponent(match[1].replace(/\+/g, ' '))
                  : defvalue;
          }

          function spice_error(e)
          {
              disconnect();
              if (e !== undefined && e.message === "Permission denied.") {
                var pass = prompt("Password");
                connect(pass);
              }
          }

          function connectToTerminal(uri, hostId, project, instance, password = undefined)
          {
              var host, port, scheme = "ws://";

              // By default, use the host and port of server that served this file
              host = spice_query_var('host', window.location.hostname);

              // Note that using the web server port only makes sense
              //  if your web server has a reverse proxy to relay the WebSocket
              //  traffic to the correct destination port.
              var default_port = window.location.port;
              if (!default_port) {
                  if (window.location.protocol == 'http:') {
                      default_port = 80;
                  }
                  else if (window.location.protocol == 'https:') {
                      default_port = 443;
                  }
              }
              port = spice_query_var('port', default_port);
              if (window.location.protocol == 'https:') {
                  scheme = "wss://";
              }

              // If a token variable is passed in, set the parameter in a cookie.
              // This is used by nova-spiceproxy.
              var token = spice_query_var('token', null);
              if (token) {
                  spice_set_cookie('token', token, 1)
              }

              if (password === undefined) {
                  password = spice_query_var('password', '');
              }
              var path = spice_query_var('path', '/node/terminal');

              if ((!host) || (!port)) {
                  return;
              }

              if (sc) {
                  sc.stop();
              }

              uri = scheme + host + ":" + port;

              if (path) {
                uri += path[0] == '/' ? path : ('/' + path);
              }

              uri = `${uri}/?ws_token=${userDetails.apiToken}&user_id=${userDetails.userId}&hostId=${hostId}&project=${project}&instance=${instance}`

              try
              {
                  sc = new SpiceHtml5.SpiceMainConn({uri: uri, screen_id: "spice-screen", password: password, onerror: spice_error, onagent: agent_connected });
              }
              catch (e)
              {
                  alert(e.toString());
                  disconnect();
              }

          }

          function disconnect()
          {
              if (sc) {
                  sc.stop();
              }
              if (window.File && window.FileReader && window.FileList && window.Blob)
              {
                  var spice_xfer_area = document.getElementById('spice-xfer-area');
                  if (spice_xfer_area != null) {
                    document.getElementById('spice-area').removeChild(spice_xfer_area);
                  }
                  document.getElementById('spice-area').removeEventListener('dragover', SpiceHtml5.handle_file_dragover, false);
                  document.getElementById('spice-area').removeEventListener('drop', SpiceHtml5.handle_file_drop, false);
              }
          }

          function agent_connected(sc)
          {
              window.addEventListener('resize', SpiceHtml5.handle_resize);
              window.spice_connection = this;

              SpiceHtml5.resize_helper(this);

              if (window.File && window.FileReader && window.FileList && window.Blob)
              {
                  var spice_xfer_area = document.createElement("div");
                  spice_xfer_area.setAttribute('id', 'spice-xfer-area');
                  document.getElementById('spice-area').appendChild(spice_xfer_area);
                  document.getElementById('spice-area').addEventListener('dragover', SpiceHtml5.handle_file_dragover, false);
                  document.getElementById('spice-area').addEventListener('drop', SpiceHtml5.handle_file_drop, false);
              }
              else
              {
                  console.log("File API is not supported");
              }
          }

          window.disconnectFromTerminal = disconnect
          window.connectToTerminal = connectToTerminal
      </script>

      <div id="containerTerminal" class="instanceViewBox">
          <div class="row m-0 p-0 h-100">
              <div class="col-md-12 text-center h-100 bg-secondary m-0 p-0">
                  <div id="spice-area">
                      <div id="spice-screen" class="spice-screen">
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <style>
        #spice-screen {
            margin: 0px !important;
        }
      </style>

      <script>
      $(function(){
          $("#spice-screen").append(`<h4 id="spiceLoadingIndicator"> <i class="fas fa-cog fa-spin"></i> </h4>`)
          let project = $("#instanceProject").text();

          // window.disconnectFromTerminal();
          window.connectToTerminal(undefined, <?= $hostId ?>, '<?= $project ?>', '<?= $instance ?>');
      })

      </script>
  </body>
</html>
