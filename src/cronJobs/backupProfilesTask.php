<?php

use Crunz\Schedule;
use dhope0000\LXDClient\Objects\Backups\BackupSchedule;

$schedule = new Schedule();

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$env = new Dotenv\Dotenv(__DIR__ . "/../../");
$env->load();

$fetchBackupSchedules = $container->make("dhope0000\LXDClient\Model\Hosts\Backups\Profiles\Schedules\FetchProfilesBackupSchedules");
$schedules = $fetchBackupSchedules->fetchActiveSchedsGroupedByHostId();
var_dump($schedules);

$executeString = PHP_BINARY . '  ' . __DIR__ . '/scripts/backupProfiles.php';

$dayOfWeekList = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

$schedule = new Schedule();

$getInstanceSetting = $container->make("dhope0000\LXDClient\Model\InstanceSettings\GetSetting");
$timezone = $getInstanceSetting->getSettingLatestValue(dhope0000\LXDClient\Constants\InstanceSettingsKeys::TIMEZONE);

foreach ($schedules as $hostId => $hostSchedules) {
    foreach ($hostSchedules as $item) {
        $argString = "{$hostId} {$item["project"]} {$item["strategyId"]}";
        $name = "Backing profiles for $argString";

        // Support the original backup format that was "daily hour:minute"
        if (preg_match('/daily [0-9]{2}:[0-9]{2}/m', $item["scheduleString"])) {
            $tSchedule = new BackupSchedule("daily", [explode(" ", $item["scheduleString"])[1]]);
        } else {
            $tSchedule = $createBackupSchedule->parseString($item["scheduleString"]);
        }

        if ($tSchedule->getRange() == "daily") {
            foreach ($tSchedule->getTimes() as $time) {
                $task = $schedule->run("$executeString $argString")->description($name . " daily@" . $time);
                $task->timezone($timezone);
                $task->daily()->at($time);
            }
        } elseif ($tSchedule->getRange() == "weekly") {
            foreach ($tSchedule->getDaysOfWeek() as $dayOfWeek) {
                foreach ($tSchedule->getTimes() as $time) {
                    $task = $schedule->run("$executeString $argString")->description($name . " " . $dayOfWeekList[$dayOfWeek] . "@" . $time);
                    $task->timezone($timezone);
                    $task->weeklyOn($dayOfWeek, $time);
                }
            }
        } elseif ($tSchedule->getRange() == "monthly") {
            foreach ($tSchedule->getTimes() as $time) {
                $tParts = explode(":", $time);
                $task = $schedule->run("$executeString $argString")->description($name . " monthly@" . $time);
                $task->timezone($timezone);
                $task->cron("{$tParts[1]} {$tParts[0]} {$tSchedule->getDayOfMonth()} * *");
            }
        } else {
            throw new \Exception("Unsupported backup schedule", 1);
        }
    }
}

return $schedule;
