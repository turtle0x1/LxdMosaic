<?php

use Crunz\Schedule;

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

(\Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'))->load();

$getInstanceSetting = $container->make(dhope0000\LXDClient\Model\InstanceSettings\GetSetting::class);

$monitorTimers = $getInstanceSetting->getSettingLatestValue(dhope0000\LXDClient\Constants\InstanceSettingsKeys::TIMERS_MONITOR);

if (empty($monitorTimers) || $monitorTimers == 0) {
    return new Schedule();
}

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/storeTimersSnapshot.php');
$task
    ->daily()
    ->at("23:59:50")
    ->description('Take daily snapshot of timers in instances');

return $schedule;
