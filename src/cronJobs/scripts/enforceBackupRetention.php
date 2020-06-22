<?php

$_ENV = getenv();

require __DIR__ . "/../../../vendor/autoload.php";

use dhope0000\LXDClient\Constants\Constants;

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$enforce = $container->make("dhope0000\LXDClient\Tools\Backups\EnforceRetentionRules");

$enforce->enforce();
