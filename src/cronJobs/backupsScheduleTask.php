<?php

use Crunz\Schedule;

$schedule = new Schedule();

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$env = new Dotenv\Dotenv(__DIR__ . "/../../");
$env->load();

$o = $container->make("dhope0000\LXDClient\Tools\Hosts\Backups\Schedules\GetAllHostsSchedules");

$clustersAndStandalone = $o->get();

function addSchedulesToSchedule(Schedule &$schedule, $host)
{
    $scheduleItems = $host->getCustomProp("schedules");
    $executeString = PHP_BINARY . '  ' . __DIR__ . '/scripts/backupContainer.php';

    foreach ($scheduleItems as $item) {
        $argString = "{$item["hostId"]} {$item["instance"]} {$item["project"]} {$item["strategyId"]}";
        $name = "Backing up $argString";

        $task = $schedule->run("$executeString $argString")->description($name);

        $scheduleParts = explode(" ", $item["scheduleString"]);

        if ($scheduleParts[0] == "daily") {
            $task->daily()->at($scheduleParts[1]);
        } else {
            throw new \Exception("Unsupported backup schedule", 1);
        }
    }
}

foreach ($clustersAndStandalone["clusters"] as $cluster) {
    foreach ($cluster["members"] as $member) {
        addSchedulesToSchedule($schedule, $member);
    }
}

foreach ($clustersAndStandalone["standalone"]["members"] as $member) {
    addSchedulesToSchedule($schedule, $member);
}


return $schedule;
