<?php

$_ENV = getenv();
date_default_timezone_set('UTC');
require __DIR__ . '/../../../vendor/autoload.php';

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$removeAnalytics = $container->make(dhope0000\LXDClient\Tools\Hosts\Timers\RemoveTimersSnapshots::class);

$removeAnalytics->remove();
