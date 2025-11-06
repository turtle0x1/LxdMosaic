<?php

use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../../vendor/autoload.php';

date_default_timezone_set('UTC');

$builder = new \DI\ContainerBuilder();
$builder->useAnnotations(true);
$container = $builder->build();

$exceptionHandler = new dhope0000\LXDClient\App\ExceptionHandler();
$exceptionHandler->register();

$env = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$env->load();
if (!isset($_ENV['DB_SQLITE']) && !empty($_ENV['DB_SQLITE'])) {
    $env->required(['DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME']);
}

$container->injectOn($exceptionHandler);

$router = $container->make(dhope0000\LXDClient\App\RouteController::class);

$request = Request::createFromGlobals();

$router->routeRequest($request);
