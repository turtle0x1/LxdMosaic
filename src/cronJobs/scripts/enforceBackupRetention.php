<?php

$_ENV = getenv();
date_default_timezone_set('UTC');
require __DIR__ . '/../../../vendor/autoload.php';

$container = new \DI\Container();

$enforce = $container->make("dhope0000\LXDClient\Tools\Backups\EnforceRetentionRules");

$enforce->enforce();
