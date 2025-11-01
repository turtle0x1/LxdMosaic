<?php

namespace dhope0000\LXDClient\App;

use dhope0000\LXDClient\App\RouteApi;
use dhope0000\LXDClient\Tools\User\UserSession;
use dhope0000\LXDClient\Tools\User\LogUserIn;
use dhope0000\LXDClient\Tools\User\ValidateToken;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use Symfony\Component\HttpFoundation\Request;

class RouteController
{
    private $validateToken;
    private $userSession;
    private $logUserIn;
    private $routeApi;
    private $routeView;
    private $session;
    private $fetchUserDetails;

    public $loginError = null;

    public function __construct(
        UserSession $userSession,
        LogUserIn $logUserIn,
        RouteApi $routeApi,
        ValidateToken $validateToken,
        FetchUserDetails $fetchUserDetails
    ) {
        $this->validateToken = $validateToken;
        $this->userSession = $userSession;
        $this->logUserIn = $logUserIn;
        $this->routeApi = $routeApi;
        $this->session = new Session(new NativeSessionStorage(), new NamespacedAttributeBag());
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function routeRequest(Request $request)
    {
        $path = trim($request->getPathInfo(), '/');

        $canSkipAuth = in_array($path, [
            "assets/lxdMosaic/logo.png",
            "assets/dist/login.dist.css",
            "assets/dist/login.dist.js",
            "assets/dist/external.fontawesome.css",
            "assets/dist/fontawesome/fa-solid-900.ttf",
            "assets/dist/fontawesome/fa-solid-900.woff",
            "assets/dist/fontawesome/fa-solid-900.woff2",
            "api/InstanceSettings/FirstRunController/run",
            'assets/lxdMosaic/favicons/android-icon-192x192.png',
            "assets/lxdMosaic/favicons/favicon-16x16.png"
        ], true);

        $adminPassBlank = $this->fetchUserDetails->adminPasswordBlank();
        if ($adminPassBlank && !$canSkipAuth) {
            if($path !== "first-run"){
                header("Location: /first-run");
                exit;
            }
            $this->routeApi->route($request, ["userid"=>1]);
            exit;
        }

        $headers = null;
        if (substr($path, 0, strlen('api')) === 'api') {
            $headers = $this->validateApiToken($request, $canSkipAuth);
        } else {
            $this->session->start();
            $headers = $this->validateSession($request, $path, $canSkipAuth);
        }

        if ($headers == null) {
            throw new \Exception("");
        }

        $this->routeApi->route($request, $headers, false);
        return true;
    }

    private function validateApiToken($request, $canSkipAuth): array
    {
        $headers = ["userid" => 1];

        if (!$canSkipAuth) {
            $headers = [
                "userid"   => $request->headers->get("userid"),
                "apitoken" => $request->headers->get("apitoken"),
                "project" => $request->headers->get("project"),
            ];

            if (!$headers["userid"] || !$headers["apitoken"]) {
                http_response_code(403);
                echo json_encode(["error" => "Missing either user id or token"]);
                exit;
            }

            if (!$this->validateToken->validate($headers["userid"], $headers["apitoken"])) {
                http_response_code(403);
                echo json_encode(["error" => "Not valid token"]);
                exit;
            }
        }
        return $headers;
    }

    private function validateSession($request, $path, $canSkipAuth): array
    {
        $loginSet = $request->request->has("login");

        if (!$this->userSession->isLoggedIn() && !$loginSet && !$canSkipAuth) {
            http_response_code(403);
            require __DIR__ . "/../../views/login.php";
            exit;
        } elseif ($loginSet) {
            try {
                $this->logUserIn->login(
                    $request->request->get("username"),
                    $request->request->get("password")
                );
                
                header("Location: " . $request->getPathInfo());
                exit;
            } catch (\Throwable $e) {
                $this->loginError = $e->getMessage();
                http_response_code(403);
                require __DIR__ . "/../../views/login.php";
                exit;
            }
        } elseif ($path === "logout") {
            $this->userSession->logout();
            header("Location: /");
            exit;
        }

        $headers = ["userid" => 1];
        if ($this->userSession->isLoggedIn()) {
            $headers = ["userid" => $this->userSession->getUserId()];
        }
        return $headers;
    }
}
