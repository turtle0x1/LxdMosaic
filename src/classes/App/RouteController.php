<?php
namespace dhope0000\LXDClient\App;

use dhope0000\LXDClient\App\RouteApi;
use dhope0000\LXDClient\App\RouteView;
use dhope0000\LXDClient\App\RouteAssets;

class RouteController
{
    public function __construct(
        RouteApi $routeApi,
        RouteView $routeView,
        RouteAssets $routeAssets
    ) {
        $this->routeApi = $routeApi;
        $this->routeView = $routeView;
        $this->routeAssets = $routeAssets;
    }

    public function routeRequest($explodedPath)
    {
        if (!isset($explodedPath[0]) || (
                $explodedPath[0] == "index" ||
                $explodedPath[0] == "views"
        )) {
            $this->routeView->route($explodedPath);
        } elseif ($explodedPath[0] == "api") {
            $this->routeApi->route($explodedPath, $_POST);
        } elseif ($explodedPath[0] == "assets") {
            $this->routeAssets->route($explodedPath);
        } elseif ($explodedPath[0] == "socket.io") {
            $port = '3000';

            header('Location: '
                . $_SERVER['REQUEST_SCHEME']
                . '://' . $_SERVER['HTTP_HOST'] . ':' . $port
                . $_SERVER['REQUEST_URI']);
        } else {
            throw new \Exception("Dont understand the path", 1);
        }

        return true;
    }
}
