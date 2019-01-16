<?php

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;

require __DIR__ . "/../../vendor/autoload.php";

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$exceptionHandler = new dhope0000\LXDClient\App\ExceptionHandler();
$exceptionHandler->register();

$env = new Dotenv\Dotenv(__DIR__ . "/../../");
$env->load();
$env->required(['DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME']);

$session = new Session(new NativeSessionStorage(), new NamespacedAttributeBag());
$session->start();

$router = $container->make("dhope0000\LXDClient\App\RouteController");

$path = ltrim($_SERVER['REQUEST_URI'], '/');    // Trim leading slash(es)
$explodedPath = array_filter(explode('/', $path));  // Split path on slashes

$router->routeRequest($explodedPath);
