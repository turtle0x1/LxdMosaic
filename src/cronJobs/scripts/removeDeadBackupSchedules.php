<?php

$_ENV = getenv();
date_default_timezone_set('UTC');
require __DIR__ . '/../../../vendor/autoload.php';

$container = new \DI\Container();

$removeDead = $container->make("dhope0000\LXDClient\Tools\Backups\Schedule\RemoveDeadBackupSchedules");

$removeDead->remove();
