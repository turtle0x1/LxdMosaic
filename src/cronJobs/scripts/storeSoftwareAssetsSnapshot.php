<?php

$_ENV = getenv();

date_default_timezone_set('UTC');

require __DIR__ . '/../../../vendor/autoload.php';

$container = new \DI\Container();

$getSoftwareAssetsSnapshotData = $container->make(
    "dhope0000\LXDClient\Tools\Hosts\SoftwareAssets\GetSoftwareAssetsSnapshotData"
);
$insertSoftwareAssets = $container->make("dhope0000\LXDClient\Model\Hosts\SoftwareAssets\InsertSoftwareAssetsSnapshot");

$date = new \DateTimeImmutable();

$softwareAssets = $getSoftwareAssetsSnapshotData->get();
$insertSoftwareAssets->insert($date, $softwareAssets);
