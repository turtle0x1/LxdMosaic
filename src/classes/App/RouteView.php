<?php
namespace dhope0000\LXDClient\App;

use \DI\Container;

class RouteView
{
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function route($request)
    {
        if (empty($request->getRequestUri())) {
            require __DIR__ . "/../../views/index.php";
        } elseif (strpos($request->getRequestUri(), "/login") === 0) {
            require __DIR__ . "/../../views/login.php";
        } elseif (strpos($request->getRequestUri(), "/terminal") === 0) {
            require __DIR__ . "/../../views/vmTerminal.php";
        } elseif (strpos($request->getRequestUri(), "/firstRun") === 0) {
            require __DIR__ . "/../../views/firstRun.php";
        } else {
            require __DIR__ . "/../../views/index.php";
        }
        return true;
    }
}
