<?php

use Crunz\Schedule;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Objects\Backups\BackupSchedule;

$schedule = new Schedule();

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$env = new Dotenv\Dotenv(__DIR__ . "/../../");
$env->load();

$createBackupSchedule = $container->make("dhope0000\LXDClient\Tools\Backups\Schedule\BackupStringToObject");

// If this task is called before checking for offline hosts we need to make sure
// that it doesn't get caught up when hosts have gone offline.
//
// Because this blocks other scripts from running we have to check all the hosts,
// which means calling this recursively. I've set a max depth of 10 to prevent
// resource consumption causing more errors.

// If more than 10 hosts are offline when this script runs 1 minute later it
// will take of the next 10 (if no admin starts getting there first)
function getHostsAndClusters($container, $depth = 0)
{
    // If more than 10 hosts have gone offline just bail
    if ($depth >= 10) {
        throw new \Exception("Lots of hosts offline", 1);
    }

    try {
        return $container->make("dhope0000\LXDClient\Tools\Hosts\Backups\Schedules\GetAllHostsSchedules")->get();
    } catch (\Throwable $e) {
        $getDetails = $container->make("dhope0000\LXDClient\Model\Hosts\GetDetails");
        $changeStatus = $container->make("dhope0000\LXDClient\Model\Hosts\ChangeStatus");
        $offlineHostMessage = "cURL error 7: Failed to connect to";
        if (StringTools::stringStartsWith($e->getMessage(), $offlineHostMessage)) {
            $host = trim(StringTools::getStringBetween($e->getMessage(), $offlineHostMessage, "port"));
            $port = trim(StringTools::getStringBetween($e->getMessage(), "port", ":"));
            $url = "https://$host:$port";
            $hostId = $getDetails->getIdByUrlMatch($url);
            if (is_numeric($hostId)) {
                $changeStatus->setOffline($hostId);
            }
        }
        $depth++;
        return getHostsAndClusters($container, $depth);
    }
}

$clustersAndStandalone = getHostsAndClusters($container);

$getInstanceSetting = $container->make("dhope0000\LXDClient\Model\InstanceSettings\GetSetting");
$timezone = $getInstanceSetting->getSettingLatestValue(dhope0000\LXDClient\Constants\InstanceSettingsKeys::TIMEZONE);

function addSchedulesToSchedule(Schedule &$schedule, $host, $createBackupSchedule, $timezone)
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

foreach ($clustersAndStandalone["clusters"] as $cluster) {
    foreach ($cluster["members"] as $member) {
        addSchedulesToSchedule($schedule, $member, $createBackupSchedule, $timezone);
    }
}

foreach ($clustersAndStandalone["standalone"]["members"] as $member) {
    addSchedulesToSchedule($schedule, $member, $createBackupSchedule, $timezone);
}


return $schedule;
