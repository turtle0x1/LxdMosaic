<?php

use Crunz\Schedule;
use dhope0000\LXDClient\Objects\Backups\BackupSchedule;

$cronSchedule = new Schedule();

$container = (new \DI\ContainerBuilder())->build();

(\Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'))->load();

$fetchBackupSchedules = $container->make(
    "dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\FetchBackupSchedules"
);
$createBackupSchedule = $container->make("dhope0000\LXDClient\Tools\Backups\Schedule\BackupStringToObject");
$getInstanceSetting = $container->make("dhope0000\LXDClient\Model\InstanceSettings\GetSetting");

$timezone = $getInstanceSetting->getSettingLatestValue(dhope0000\LXDClient\Constants\InstanceSettingsKeys::TIMEZONE);
$executeString = PHP_BINARY . '  ' . __DIR__ . '/scripts/backupContainer.php';
$dayOfWeekList = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$hostBackups = $fetchBackupSchedules->fetchActiveSchedsGroupedByHostId();

foreach ($hostBackups as $hostId => $schedules) {
    foreach ($schedules as $schedule) {
        $argString = "{$hostId} {$schedule['instance']} {$schedule['project']} {$schedule['strategyId']}";
        $name = "Backing up {$argString}";

        // Support the original backup format that was "daily hour:minute"
        if (preg_match('/daily [0-9]{2}:[0-9]{2}/m', (string) $schedule['scheduleString'])) {
            $tSchedule = new BackupSchedule('daily', [explode(' ', (string) $schedule['scheduleString'])[1]]);
        } else {
            $tSchedule = $createBackupSchedule->parseString($schedule['scheduleString']);
        }

        if ($tSchedule->getRange() == 'daily') {
            foreach ($tSchedule->getTimes() as $time) {
                $task = $cronSchedule->run("{$executeString} {$argString}")
                    ->description($name . ' daily@' . $time);
                $task->timezone($timezone);
                $task->daily()
                    ->at($time);
            }
        } elseif ($tSchedule->getRange() == 'weekly') {
            foreach ($tSchedule->getDaysOfWeek() as $dayOfWeek) {
                foreach ($tSchedule->getTimes() as $time) {
                    $task = $cronSchedule->run("{$executeString} {$argString}")
                        ->description($name . ' ' . $dayOfWeekList[$dayOfWeek] . '@' . $time);
                    $task->timezone($timezone);
                    $task->weeklyOn($dayOfWeek, $time);
                }
            }
        } elseif ($tSchedule->getRange() == 'monthly') {
            foreach ($tSchedule->getTimes() as $time) {
                $tParts = explode(':', (string) $time);
                $task = $cronSchedule->run("{$executeString} {$argString}")
                    ->description($name . ' monthly@' . $time);
                $task->timezone($timezone);
                $task->cron("{$tParts[1]} {$tParts[0]} {$tSchedule->getDayOfMonth()} * *");
            }
        } else {
            throw new \Exception('Unsupported backup schedule', 1);
        }
    }
}

return $cronSchedule;
