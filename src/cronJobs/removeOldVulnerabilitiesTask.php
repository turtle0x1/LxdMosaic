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
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/removeOldVulnerabilities.php');
$task
    ->daily()
    ->at('03:00')
    ->description('Remove old vulnerability data (older than configured retention)')
    ->timezone($timezone);

return $schedule;
