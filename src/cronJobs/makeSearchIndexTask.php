<?php

use Crunz\Schedule;

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

(\Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'))->load();

$getInstanceSetting = $container->make(dhope0000\LXDClient\Model\InstanceSettings\GetSetting::class);

$createSearchIndex = $getInstanceSetting->getSettingLatestValue(
    dhope0000\LXDClient\Constants\InstanceSettingsKeys::SEARCH_INDEX
);

if (empty($createSearchIndex) || $createSearchIndex == 0) {
    return new Schedule();
}

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/makeSearchIndex.php');
$task
    ->everyFifteenMinutes()
    ->description('Make search index');

return $schedule;
