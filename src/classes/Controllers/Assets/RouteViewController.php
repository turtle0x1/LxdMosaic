<?php

namespace dhope0000\LXDClient\Controllers\Assets;

use \DI\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RouteViewController
{
    private $container;
    
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    /**
     * @Route("/", name="Root page")
     * @Route("/login", name="Login page")
     * @Route("/terminal", name="Terminal page")
     * @Route("/first-run", name="First run page")
     */
    public function route(Request $request): Response
    {
        $path = trim($request->getPathInfo(), '/');
        $file = __DIR__ . "/../../../views/index.php";

        if ($path === '' || $path === 'index') {
            $file = __DIR__ . "/../../../views/index.php";
        } elseif ($path === 'login') {
            $file = __DIR__ . "/../../../views/login.php";
        } elseif ($path === 'terminal') {
            $file = __DIR__ . "/../../../views/vmTerminal.php";
        } elseif ($path === 'first-run') {
            $file = __DIR__ . "/../../../views/firstRun.php";
        }

        ob_start();
        require $file;
        $contents = ob_get_contents();
        ob_clean();
        
        return new Response($contents, 200, [
            'Content-Type' => 'text/html',
            'Cache-Control' => 'public',
        ]);
    }
}
