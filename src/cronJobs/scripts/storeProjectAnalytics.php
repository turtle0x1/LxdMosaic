<?php

$_ENV = getenv();
date_default_timezone_set('UTC');
require __DIR__ . '/../../../vendor/autoload.php';

$container = new \DI\Container();

$storeAnalytics = $container->make("dhope0000\LXDClient\Tools\ProjectAnalytics\StoreProjectsOverview");

$storeAnalytics->storeCurrent();
