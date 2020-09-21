<?php

use Crunz\Schedule;
use dhope0000\LXDClient\Objects\Backups\BackupSchedule;

$schedule = new Schedule();

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$env = new Dotenv\Dotenv(__DIR__ . "/../../");
$env->load();

$o = $container->make("dhope0000\LXDClient\Tools\Hosts\Backups\Schedules\GetAllHostsSchedules");
$createBackupSchedule = $container->make("dhope0000\LXDClient\Tools\Backups\Schedule\BackupStringToObject");
$clustersAndStandalone = $o->get();

function addSchedulesToSchedule(Schedule &$schedule, $host, $createBackupSchedule)
{
    $scheduleItems = $host->getCustomProp("schedules");
    $executeString = PHP_BINARY . '  ' . __DIR__ . '/scripts/backupContainer.php';

    $dayOfWeekList = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    foreach ($scheduleItems as $item) {
        $argString = "{$item["hostId"]} {$item["instance"]} {$item["project"]} {$item["strategyId"]}";
        $name = "Backing up $argString";

        // Support the original backup format that was "daily hour:minute"
        if (preg_match('/daily [0-9]{2}:[0-9]{2}/m', $item["scheduleString"])) {
            $tSchedule = new BackupSchedule("daily", [explode(" ", $item["scheduleString"])[1]]);
        } else {
            $tSchedule = $createBackupSchedule->parseString($item["scheduleString"]);
        }

        if ($tSchedule->getRange() == "daily") {
            foreach ($tSchedule->getTimes() as $time) {
                $task = $schedule->run("$executeString $argString")->description($name . " daily@" . $time);
                $task->daily()->at($time);
            }
        } elseif ($tSchedule->getRange() == "weekly") {
            foreach ($tSchedule->getDaysOfWeek() as $dayOfWeek) {
                foreach ($tSchedule->getTimes() as $time) {
                    $task = $schedule->run("$executeString $argString")->description($name . " " . $dayOfWeekList[$dayOfWeek] . "@" . $time);
                    $task->weeklyOn($dayOfWeek, $time);
                }
            }
        } elseif ($tSchedule->getRange() == "monthly") {
            foreach ($tSchedule->getTimes() as $time) {
                $tParts = explode(":", $time);
                $task = $schedule->run("$executeString $argString")->description($name . " monthly@" . $time);
                $task->cron("{$tParts[1]} {$tParts[0]} {$tSchedule->getDayOfMonth()} * *");
            }
        } else {
            throw new \Exception("Unsupported backup schedule", 1);
        }
    }
}

foreach ($clustersAndStandalone["clusters"] as $cluster) {
    foreach ($cluster["members"] as $member) {
        addSchedulesToSchedule($schedule, $member, $createBackupSchedule);
    }
}

foreach ($clustersAndStandalone["standalone"]["members"] as $member) {
    addSchedulesToSchedule($schedule, $member, $createBackupSchedule);
}


return $schedule;
