<?php

use Crunz\Schedule;

$container = new \DI\Container();

(\Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'))->load();

$getInstanceSetting = $container->make("dhope0000\LXDClient\Model\InstanceSettings\GetSetting");
$timezone = $getInstanceSetting->getSettingLatestValue(dhope0000\LXDClient\Constants\InstanceSettingsKeys::TIMEZONE);

$vulnMonitor = $getInstanceSetting->getSettingLatestValue(
    dhope0000\LXDClient\Constants\InstanceSettingsKeys::VULNERABILITY_MONITOR
);

if (empty($vulnMonitor) || $vulnMonitor == 0) {
    return new Schedule();
}

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/updateVulnerabilities.php');
$task
    ->daily()
    ->at('02:00')
    ->description('Update vulnerability database and scan impacted instances')
    ->timezone($timezone);

return $schedule;
