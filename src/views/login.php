<!DOCTYPE html>
<!-- Stolen straight from the bootstrap example -->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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

    <title>LXD Mosaic</title>

    <link rel="stylesheet" href="/assets/dist/login.dist.css">
    <script src="/assets/dist/login.dist.js"></script>

    <style>
        html, body {
          height: 100%;
        }

        body {
          display: -ms-flexbox;
          display: -webkit-box;
          display: flex;
          -ms-flex-align: center;
          -ms-flex-pack: center;
          -webkit-box-align: center;
          align-items: center;
          -webkit-box-pack: center;
          justify-content: center;
          padding-top: 40px;
          padding-bottom: 40px;
          background-color: #f5f5f5;
        }

        .form-signin {
          width: 100%;
          max-width: 330px;
          padding: 15px;
          margin: 0 auto;
        }
        .form-signin .checkbox {
          font-weight: 400;
        }
        .form-signin .form-control {
          position: relative;
          box-sizing: border-box;
          height: auto;
          padding: 10px;
          font-size: 16px;
        }
        .form-signin .form-control:focus {
          z-index: 2;
        }
        #username {
          margin-bottom: -1px;
          border-bottom-right-radius: 0;
          border-bottom-left-radius: 0;
        }
        #password {
          margin-bottom: 10px;
          border-top-left-radius: 0;
          border-top-right-radius: 0;
        }
        </style>
  </head>
  <body class="text-center">
      <form class="form-signin" action="/" method="POST">
        <img class="mb-4" src="/assets/lxdMosaic/logo.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">LXD Mosaic</h1>
        <label for="inputEmail" class="visually-hidden">Username</label>
        <input id="username" class="form-control" placeholder="Username" name="username" required="" autofocus="">
        <label for="inputPassword" class="visually-hidden">Password</label>
        <input type="password" id="password" class="form-control" name="password" placeholder="Password" required="">
        <?= $this->loginError == null ? "" : "<div class='text-danger'>! {$this->loginError}</div>" /** @phpstan-ignore-line */ ?>
        <button class="btn btn-lg btn-primary btn-block" name="login" value="1" type="submit">Sign in</button>
      </form>
  </body>
</html>
