<?php

$_ENV = getenv();
date_default_timezone_set("UTC");
require __DIR__ . "/../../../vendor/autoload.php";

use dhope0000\LXDClient\Constants\Constants;

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$storeAnalytics = $container->make("dhope0000\LXDClient\Tools\ProjectAnalytics\StoreProjectsOverview");

$storeAnalytics->storeCurrent();
