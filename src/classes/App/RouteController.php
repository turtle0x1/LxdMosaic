<?php
namespace dhope0000\LXDClient\App;

use dhope0000\LXDClient\App\RouteApi;
use dhope0000\LXDClient\App\RouteView;
use dhope0000\LXDClient\App\RouteAssets;
use dhope0000\LXDClient\Tools\User\UserSession;
use dhope0000\LXDClient\Tools\User\LogUserIn;
use dhope0000\LXDClient\Tools\User\ValidateToken;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class RouteController
{
    public $loginError = null;

    public function __construct(
        UserSession $userSession,
        LogUserIn $logUserIn,
        RouteApi $routeApi,
        RouteView $routeView,
        RouteAssets $routeAssets,
        ValidateToken $validateToken,
        FetchUserDetails $fetchUserDetails
    ) {
        $this->validateToken = $validateToken;
        $this->userSession = $userSession;
        $this->logUserIn = $logUserIn;
        $this->routeApi = $routeApi;
        $this->routeView = $routeView;
        $this->routeAssets = $routeAssets;
        $this->session = new Session(new NativeSessionStorage(), new NamespacedAttributeBag());
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function routeRequest($request, $context)
    {
        $canSkipAuth = in_array($request->getRequestUri(), [
            "/assets/lxdMosaic/logo.png",
            "/assets/dist/login.dist.css",
            "/assets/dist/login.dist.js",
            "/assets/dist/external.fontawesome.css",
            "/assets/fontawesome/webfonts/fa-solid-900.ttf",
            "/api/InstanceSettings/FirstRunController/run"
        ]);

        $adminPassBlank = $this->fetchUserDetails->adminPasswordBlank();

        if ($adminPassBlank == true && !$canSkipAuth) {
            $this->routeView->route(["views", "firstRun"]);
            exit;
        }

        if (strpos($request->getRequestUri(), "/api") === 0) {
            $headers = ["userid"=>1];

            if (!$canSkipAuth) {
                $headers = $request->headers->all();

                $userId = $request->headers->get("userid");
                $apiToken = $request->headers->get("apitoken");

                if ($userId == null || $apiToken == null) {
                    http_response_code(403);
                    echo json_encode(["error"=>"Missing either user id or token"]);
                    exit;
                }

                if (!$this->validateToken->validate($userId, $apiToken)) {
                    http_response_code(403);
                    echo json_encode(["error"=>"Not valid token"]);
                    exit;
                }
            }

            $this->routeApi->route($request, $context);
            exit;
        }

        $this->session->start();

        $loginSet = isset($_POST["login"]);

        if ($this->userSession->isLoggedIn() !== true && !$loginSet && !$canSkipAuth) {
            http_response_code(403);
            require __DIR__ . "/../../views/login.php";
            exit;
        } elseif ($loginSet) {
            //TODO fairly certian this creates a vunreabilty but this whole
            //     thing is a mess
            try {
                $this->logUserIn->login($_POST["username"], $_POST["password"]);
            } catch (\Throwable $e) {
                $this->loginError = $e->getMessage();
                http_response_code(403);
                require __DIR__ . "/../../views/login.php";
                return false;
            }
        } elseif (strpos($request->getRequestUri(), "/logout") === 0) {
            $this->userSession->logout();
            header("Location: /");
            exit;
        }

        if (strpos($request->getRequestUri(), "/assets") === 0) {
            $this->routeAssets->route($request);
        } elseif (strpos($request->getRequestUri(), "/terminals") === 0) {
            $port = '3000';

            $url = $_SERVER['REQUEST_SCHEME']
            . '://localhost:' . $port
            . $_SERVER['REQUEST_URI'];

            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents('php://input'));
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));

            $server_output = curl_exec($ch);
            curl_close($ch);

            echo $server_output;
        } else {
            $this->routeView->route($request);
        }

        return true;
    }
}
