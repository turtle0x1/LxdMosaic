<?php
namespace dhope0000\LXDClient\App;

use \DI\Container;

class RouteView
{
    /** @phpstan-ignore-next-line */
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function route(array $pathParts)
    {
        if (empty($pathParts)) {
            require __DIR__ . "/../../views/index.php";
        } elseif ($pathParts[0] == "login") {
            require __DIR__ . "/../../views/login.php";
        } elseif ($pathParts[0] == "terminal") {
            require __DIR__ . "/../../views/vmTerminal.php";
        } elseif (isset($pathParts[1]) && $pathParts[1] == "firstRun") {
            require __DIR__ . "/../../views/firstRun.php";
        } else {
            require __DIR__ . "/../../views/index.php";
        }
        return true;
    }
}
