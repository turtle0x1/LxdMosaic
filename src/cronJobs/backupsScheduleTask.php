<?php

use Crunz\Schedule;

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

        $scheduleParts = explode(" ", $item["scheduleString"]);

        $tSchedule = $createBackupSchedule->parseString($item["scheduleString"]);

        if ($tSchedule->getRange() == "daily") {
            $task = $schedule->run("$executeString $argString")->description($name);
            $task->daily()->at($scheduleParts[1]);
        } elseif ($tSchedule->getRange() == "weekly") {
            foreach ($tSchedule->getDaysOfWeek() as $dayOfWeek) {
                foreach ($tSchedule->getTimes() as $time) {
                    $task = $schedule->run("$executeString $argString")->description($name . " " . $dayOfWeekList[$dayOfWeek] . "@" . $time);
                    $task->weeklyOn($dayOfWeek, $time);
                }
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
