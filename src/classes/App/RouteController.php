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
    private ValidateToken $validateToken;
    private UserSession $userSession;
    private LogUserIn $logUserIn;
    private RouteApi $routeApi;
    private RouteView $routeView;
    private RouteAssets $routeAssets;
    private Session $session;
    private FetchUserDetails $fetchUserDetails;

    public ?string $loginError = null;

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

    public function routeRequest($explodedPath)
    {
        $canSkipAuth = in_array(implode("/", $explodedPath), [
            "assets/lxdMosaic/logo.png",
            "assets/dist/login.dist.css",
            "assets/dist/login.dist.js",
            "assets/dist/external.fontawesome.css",
            "assets/dist/fontawesome/fa-solid-900.ttf",
            "assets/dist/fontawesome/fa-solid-900.woff",
            "assets/dist/fontawesome/fa-solid-900.woff2",
            "api/InstanceSettings/FirstRunController/run"
        ]);

        $adminPassBlank = $this->fetchUserDetails->adminPasswordBlank();

        if ($adminPassBlank == true && !$canSkipAuth) {
            $this->routeView->route(["views", "firstRun"]);
            exit;
        }

        if (isset($explodedPath[0]) && $explodedPath[0] == "api") {
            $headers = ["userid"=>1];

            if (!$canSkipAuth) {
                $headers = getallheaders();

                // PHP-FPM strikes again
                $headers = array_change_key_case($headers);

                if (!isset($headers["userid"]) || !isset($headers["apitoken"])) {
                    http_response_code(403);
                    echo json_encode(["error"=>"Missing either user id or token"]);
                    exit;
                }

                if (!$this->validateToken->validate($headers["userid"], $headers["apitoken"])) {
                    http_response_code(403);
                    echo json_encode(["error"=>"Not valid token"]);
                    exit;
                }
            }

            $this->routeApi->route($explodedPath, $headers);
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
        } elseif (isset($explodedPath[0]) && $explodedPath[0] == "logout") {
            $this->userSession->logout();
            header("Location: /");
            exit;
        }

        $path = "";
        if (isset($explodedPath[0])) {
            $parts = parse_url($explodedPath[0]);

            if ($parts == false) {
                throw new \Exception("Couldn't parse '{$explodedPath[0]}'", 1);
            }

            $path = $parts["path"];
        }

        if ($path == "assets") {
            $this->routeAssets->route($explodedPath);
        } else {
            $this->routeView->route($explodedPath);
        }

        return true;
    }
}
