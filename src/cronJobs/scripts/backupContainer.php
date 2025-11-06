<?php

$_ENV = getenv();
date_default_timezone_set('UTC');
require __DIR__ . '/../../../vendor/autoload.php';

use dhope0000\LXDClient\Constants\BackupStrategies;

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

if (count($argv) !== 5) {
    throw new \Exception('script should be called backupContainer.php hostId instance project strategyId', 1);
}

$hostId = $argv[1];

if (!is_numeric($hostId)) {
    throw new \Exception('host must be numeric id', 1);
}

$instance = $argv[2];
$project = $argv[3];
$strategy = $argv[4];

if (!is_numeric($strategy)) {
    throw new \Exception('Please provide strategy as numeric id', 1);
}

$getHost = $container->make("dhope0000\LXDClient\Model\Hosts\GetDetails");
$backupInstance = $container->make("dhope0000\LXDClient\Tools\Instances\Backups\BackupInstance");
$getInstanceSetting = $container->make("dhope0000\LXDClient\Model\InstanceSettings\GetSetting");
$insertInstanceBackup = $container->make("dhope0000\LXDClient\Model\Hosts\Backups\Instances\InsertInstanceBackup");
$timezone = $getInstanceSetting->getSettingLatestValue(dhope0000\LXDClient\Constants\InstanceSettingsKeys::TIMEZONE);

$host = $getHost->fetchHost($hostId);

try {
    $importAndDelete = $strategy == BackupStrategies::IMPORT_AND_DELETE;

    $backupInstance->create(
        $host,
        $instance,
        $project,
        (new \DateTime())->setTimezone(new \DateTimeZone($timezone))
            ->format('Y-m-d H:i:s'),
        true,
        $importAndDelete
    );
} catch (\Throwable $e) {
    $insertInstanceBackup->insert(
        (new \DateTime())->setTimezone(new \DateTimeZone($timezone)),
        $hostId,
        $project,
        $instance,
        (new \DateTime())->setTimezone(new \DateTimeZone($timezone))
            ->format('Y-m-d H:i:s'),
        '',
        0,
        1,
        $e->getMessage()
    );
}
