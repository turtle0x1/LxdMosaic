<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Instances\GetHostsInstances;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\FetchBackupSchedules;

class GetHostInstanceStatusForBackupSet
{
    private $getHostsInstances;
    private $lxdClient;
    private $fetchBackupSchedules;

    public function __construct(
        GetHostsInstances $getHostsInstances,
        LxdClient $lxdClient,
        FetchBackupSchedules $fetchBackupSchedules
    ) {
        $this->getHostsInstances = $getHostsInstances;
        $this->lxdClient = $lxdClient;
        $this->fetchBackupSchedules = $fetchBackupSchedules;
    }

    public function get(array $backups)
    {
        $hostsContainers = $this->getHostsInstances->getAll(true);

        $backupsByHostId = $this->createBackupsByHostIdStruct($backups);

        $groupedSchedule = $this->groupSchedules($this->fetchBackupSchedules->fetchSchedulesGroupedByHostId());

        $missingBackups = [];

        foreach ($hostsContainers as $host) {
            if (empty($host->getCustomProp("containers"))) {
                continue;
            }

            $backupsToSearch = [];

            if (isset($backupsByHostId[$host->getHostId()])) {
                $backupsToSearch = $backupsByHostId[$host->getHostId()];
            }

            $containers = [];
            $seenContainerNames = [];

            foreach ($host->getCustomProp("containers") as $name => $container) {
                $lastBackup = $this->findLastBackup($name, $backupsToSearch);
                $container = $this->extractContainerInfo($name, $container);
                $allBackups = $this->findAllBackups($name, $backupsToSearch);

                $scheduleString = "";

                if (isset($groupedSchedule[$host->getHostId()][$name])) {
                    $scheduleString = $groupedSchedule[$host->getHostId()][$name]["scheduleString"];
                }

                $container["containerExists"] = true;
                $container["lastBackup"] = $lastBackup;
                $container["allBackups"] = $allBackups;
                $container["scheduleString"] = $scheduleString;

                $containers[] = $container;
                $seenContainerNames[] = $name;
            }

            foreach ($backupsToSearch as $backup) {
                if (!in_array($backup["container"], $seenContainerNames)) {
                    $containers[] = [
                        "name"=>$backup["container"],
                        "containerExists"=>false,
                        "scheduleString"=>"N/A",
                        "lastBackup"=>$this->findLastBackup($backup["container"], $backupsToSearch),
                        "allBackups"=>$this->findAllBackups($backup["container"], $backupsToSearch)
                    ];
                    $seenContainerNames[] = $backup["container"];
                }
            }

            $host->setCustomProp("containers", $containers);
            $host->removeCustomProp("hostInfo", $containers);

            $missingBackups[$host->getAlias()] = $host;
        }
        return $missingBackups;
    }

    private function createBackupsByHostIdStruct(array $backups)
    {
        $backupsByHostId = [];
        foreach ($backups as $backup) {
            if (!isset($backupsByHostId[$backup["hostId"]])) {
                $backupsByHostId[$backup["hostId"]] = [];
            }
            $backupsByHostId[$backup["hostId"]][] = $backup;
        }
        return $backupsByHostId;
    }

    private function findAllBackups(string $container, array $hostBackups)
    {
        $output = [];
        foreach ($hostBackups as $backup) {
            if ($backup["container"] == $container) {
                $output[] = $backup;
            }
        }
        return $output;
    }

    private function findLastBackup(string $container, ?array $hostBackups)
    {
        $x = [
            "neverBackedUp"=>true
        ];

        if (empty($hostBackups)) {
            return $x;
        }

        foreach ($hostBackups as $backup) {
            if ($backup["container"] == $container) {
                $x = array_merge($x, $backup);
                $x["neverBackedUp"] = false;
            }
        }
        return $x;
    }

    private function groupSchedules(array $schedules)
    {
        foreach ($schedules as $hostId => $instances) {
            foreach ($instances as $index => $instance) {
                $schedules[$hostId][$instance["instance"]] = $instance;
                unset($schedules[$hostId][$index]);
            }
        }
        return $schedules;
    }

    private function extractContainerInfo(string $name, array $container)
    {
        return [
            "name"=>$name,
            "created"=>$container["created_at"]
        ];
    }
}
