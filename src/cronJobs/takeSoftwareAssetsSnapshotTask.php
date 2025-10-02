<?php

use Crunz\Schedule;

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

(\Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'))->load();

$getInstanceSetting = $container->make("dhope0000\LXDClient\Model\InstanceSettings\GetSetting");

$monitorSoftwareAssets = $getInstanceSetting->getSettingLatestValue(dhope0000\LXDClient\Constants\InstanceSettingsKeys::SOFTWARE_INVENTORY_MONITOR);

if (empty($monitorSoftwareAssets) || $monitorSoftwareAssets == 0) {
    return new Schedule();
}

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/storeSoftwareAssetsSnapshot.php');
$task
    ->daily()
    ->at("23:59:50")
    ->description('Take daily snapshot of software assets');

return $schedule;
