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

        $groupedSchedule = $this->groupSchedules($this->fetchBackupSchedules->fetchActiveSchedsGroupedByHostId());

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
            //TODO This all needs to be project aware and re-written
            foreach ($host->getCustomProp("containers") as $name => $container) {
                $lastBackup = $this->findLastBackup($name, $backupsToSearch);
                $container = $this->extractContainerInfo($name, $container);
                $allBackups = $this->findAllBackupsandTotalSize($name, $backupsToSearch);

                $scheduleString = "";

                if (isset($groupedSchedule[$host->getHostId()][$name])) {
                    $scheduleString = $groupedSchedule[$host->getHostId()][$name]["scheduleString"];
                }

                $container["containerExists"] = true;
                $container["lastBackup"] = $lastBackup;
                $container["totalSize"] = $allBackups["size"];
                $container["allBackups"] = $allBackups["backups"];
                $container["scheduleString"] = $scheduleString;

                $containers[] = $container;
                $seenContainerNames[] = $name;
            }

            foreach ($backupsToSearch as $backup) {
                if (!in_array($backup["container"], $seenContainerNames)) {
                    $allBackups = $this->findAllBackupsandTotalSize($backup["container"], $backupsToSearch);

                    $containers[] = [
                        "name"=>$backup["container"],
                        "containerExists"=>false,
                        "scheduleString"=>"N/A",
                        "lastBackup"=>$this->findLastBackup($backup["container"], $backupsToSearch),
                        "allBackups"=>$allBackups["backups"],
                        "totalSize"=>$allBackups["size"]
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

    private function findAllBackupsandTotalSize(string $container, array $hostBackups)
    {
        $output = [];
        $totalSize = 0;
        foreach ($hostBackups as $backup) {
            if ($backup["container"] == $container) {
                $totalSize += $backup["filesize"];
                $output[] = $backup;
            }
        }
        return [
            "backups"=>$output,
            "size"=>$totalSize
        ];
    }

    private function findLastBackup(string $container, ?array $hostBackups)
    {
        $x = [
            "neverBackedUp"=>true
        ];

        if (empty($hostBackups)) {
            return $x;
        }

        $latestDate =  null;

        foreach ($hostBackups as $backup) {
            if ($backup["container"] == $container) {
                $x["neverBackedUp"] = false;
                if ((new \DateTime($backup["backupDateCreated"])) > $latestDate) {
                    $latestDate = (new \DateTime($backup["backupDateCreated"]));
                    $x = array_merge($x, $backup);
                }
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
