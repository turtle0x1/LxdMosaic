<?php

use Crunz\Schedule;

$container = new \DI\Container();

(\Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'))->load();

$getInstanceSetting = $container->make(dhope0000\LXDClient\Model\InstanceSettings\GetSetting::class);

$monitorTimers = $getInstanceSetting->getSettingLatestValue(
    dhope0000\LXDClient\Constants\InstanceSettingsKeys::TIMERS_MONITOR
);

if (empty($monitorTimers) || $monitorTimers == 0) {
    return new Schedule();
}

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/removeTimersSnapshots.php');
$task
    ->daily()
    ->at('23:59:50')
    ->description('Remove instance timers snapshots');

return $schedule;
