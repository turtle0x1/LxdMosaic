<?php

$_ENV = getenv();
date_default_timezone_set("UTC");
require __DIR__ . "/../../../vendor/autoload.php";

use dhope0000\LXDClient\Constants\ProfileBackupStrategies;

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

if (count($argv) !== 4) {
    throw new \Exception("script should be called backupProfiles.php hostId project strategyId", 1);
}

$hostId = $argv[1];

if (!is_numeric($hostId)) {
    throw new \Exception("host must be numeric id", 1);
}

$project = $argv[2];
$strategy = $argv[3];

if (!is_numeric($strategy)) {
    throw new \Exception("Please provide strategy as numeric id", 1);
}

$getHost = $container->make("dhope0000\LXDClient\Model\Hosts\GetDetails");
$backupProfiles = $container->make("dhope0000\LXDClient\Tools\Hosts\Backups\Profiles\BackupProfiles");

$host = $getHost->fetchHost($hostId);

$backupProfiles->create(
    $host,
    $project
);
