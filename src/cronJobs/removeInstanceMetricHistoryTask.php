<?php

use Crunz\Schedule;

$container = (new \DI\ContainerBuilder())->build();

(\Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'))->load();

$getInstanceSetting = $container->make("dhope0000\LXDClient\Model\InstanceSettings\GetSetting");
$timezone = $getInstanceSetting->getSettingLatestValue(dhope0000\LXDClient\Constants\InstanceSettingsKeys::TIMEZONE);

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/removeInstanceMetrics.php');
$task
    ->hourly()
    ->description('Removing instance metric history')
    ->timezone($timezone);

return $schedule;
