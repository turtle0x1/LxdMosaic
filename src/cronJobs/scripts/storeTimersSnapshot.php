<?php

use dhope0000\LXDClient\Model\Hosts\Timers\InsertTimersSnapshot;
use dhope0000\LXDClient\Tools\Hosts\Timers\GetTimersSnapshotData;

$_ENV = getenv();

date_default_timezone_set("UTC");

require __DIR__ . "/../../../vendor/autoload.php";

$container = (new \DI\ContainerBuilder())->build();

$getSoftwareAssetsSnapshotData = $container->make(GetTimersSnapshotData::class);
$insertSoftwareAssets = $container->make(InsertTimersSnapshot::class);

$date = new \DateTimeImmutable();

$softwareAssets = $getSoftwareAssetsSnapshotData->get();
$insertSoftwareAssets->insert($date, $softwareAssets);
