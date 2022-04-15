<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher;

require __DIR__ . "/../../vendor/autoload.php";

date_default_timezone_set("UTC");

$container = (new \DI\ContainerBuilder())
    ->useAnnotations(true)
    ->build();

$exceptionHandler = new dhope0000\LXDClient\App\ExceptionHandler();
$exceptionHandler->register();

$env = new Dotenv\Dotenv(__DIR__ . "/../../");
$env->load();
if (!isset($_ENV["DB_SQLITE"]) && !empty($_ENV["DB_SQLITE"])) {
    $env->required(['DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME']);
}

$container->injectOn($exceptionHandler);

$router = $container->make("dhope0000\LXDClient\App\RouteController");

$request = Request::createFromGlobals();
$context = new RequestContext();
$context->fromRequest($request);

$router->routeRequest($request, $context);
