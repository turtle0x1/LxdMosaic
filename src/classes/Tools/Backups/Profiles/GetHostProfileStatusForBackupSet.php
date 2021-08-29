<?php

namespace dhope0000\LXDClient\Tools\Backups\Profiles;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Instances\GetHostsInstances;
use dhope0000\LXDClient\Model\Hosts\Backups\Profiles\Schedules\FetchProfilesBackupSchedules;
use dhope0000\LXDClient\Tools\Universe;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;

class GetHostProfileStatusForBackupSet
{
    private $getHostsInstances;
    private $lxdClient;
    private $fetchProfilesBackupSchedules;

    public function __construct(
        GetHostsInstances $getHostsInstances,
        LxdClient $lxdClient,
        FetchProfilesBackupSchedules $fetchProfilesBackupSchedules,
        HasExtension $hasExtension,
        Universe $universe
    ) {
        $this->getHostsInstances = $getHostsInstances;
        $this->lxdClient = $lxdClient;
        $this->fetchProfilesBackupSchedules = $fetchProfilesBackupSchedules;
        $this->universe = $universe;
        $this->hasExtension = $hasExtension;
    }

    public function get(int $userId, array $backups)
    {
        $clustersAndStandalone = $this->universe->getEntitiesUserHasAccesTo($userId, "projects");

        $backupsByHostId = $this->createBackupsByHostIdStruct($backups);

        $groupedSchedule = $this->groupSchedules($this->fetchProfilesBackupSchedules->fetchActiveSchedsGroupedByHostId());

        $missingBackups = [];

        foreach ($clustersAndStandalone["clusters"] as $cluster) {
            foreach ($cluster["members"] as $host) {
                if ($host->hostOnline()) {
                    $missingBackups[$host->getAlias()] = $this->addDetailsToHost($host, $backupsByHostId, $groupedSchedule);
                }
            }
        }

        foreach ($clustersAndStandalone["standalone"]["members"] as $host) {
            if ($host->hostOnline()) {
                $missingBackups[$host->getAlias()] = $this->addDetailsToHost($host, $backupsByHostId, $groupedSchedule);
            }
        }

        return $missingBackups;
    }

    private function addDetailsToHost($host, $backupsByHostId, $groupedSchedule)
    {
        $backupsToSearch = [];

        if (isset($backupsByHostId[$host->getHostId()])) {
            $backupsToSearch = $backupsByHostId[$host->getHostId()];
        }

        $projects = [];
        $seenProjectNames = [];

        foreach ($host->getCustomProp("projects") as $name) {
            $lastBackup = $this->findLastBackup($name, $backupsToSearch);
            $project = ["name"=>$name];
            $allBackups = $this->findAllBackupsandTotalSize($name, $backupsToSearch);

            $scheduleString = "";
            $scheduleRetention = "";
            $strategyName = "";

            if (isset($groupedSchedule[$host->getHostId()][$name])) {
                $scheduleString = $groupedSchedule[$host->getHostId()][$name]["scheduleString"];
                $scheduleRetention = $groupedSchedule[$host->getHostId()][$name]["scheduleRetention"];
                $strategyName = $groupedSchedule[$host->getHostId()][$name]["strategyName"];
            }

            $project["projectExists"] = true;
            $project["lastBackup"] = $lastBackup;
            $project["totalSize"] = $allBackups["size"];
            $project["allBackups"] = $allBackups["backups"];
            $project["scheduleString"] = $scheduleString;
            $project["scheduleRetention"] = $scheduleRetention;
            $project["strategyName"] = $strategyName;

            $projects[] = $project;

            if (!isset($seenProjectNames[$name])) {
                $seenProjectNames[$name] = [];
            }

            $seenProjectNames[$name][] = $name;
        }

        foreach ($backupsToSearch as $backup) {
            if (!isset($seenProjectNames[$backup["project"]])) {
                continue;
            }

            if (!isset($seenProjectNames[$backup["project"]])) {
                $seenProjectNames[$backup["project"]] = [];
            }

            if (!in_array($backup["project"], $seenProjectNames[$backup["project"]])) {
                $allBackups = $this->findAllBackupsandTotalSize($backup["project"], $backupsToSearch);

                $projects[] = [
                    "name"=>$backup["project"],
                    "projectExists"=>false,
                    "scheduleString"=>"N/A",
                    "lastBackup"=>$this->findLastBackup($backup["project"], $backupsToSearch),
                    "allBackups"=>$allBackups["backups"],
                    "totalSize"=>$allBackups["size"],
                    "scheduleRetention"=>"",
                    "strategyName"=>""
                ];
                $seenProjectNames[$backup["project"]][] = $backup["project"];
            }
        }


        $supportsBackups = $this->hasExtension->checkWithHost($host, LxdApiExtensions::CONTAINER_BACKUP);
        $host->setCustomProp("supportsBackups", $supportsBackups);
        $host->setCustomProp("projects", $projects);
        $host->removeCustomProp("hostInfo");
        $host->removeCustomProp("instances");

        return $host;
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

    private function findAllBackupsandTotalSize(string $project, array $hostBackups)
    {
        $output = [];
        $totalSize = 0;
        foreach ($hostBackups as $backup) {
            if ($backup["project"] == $project) {
                $totalSize += $backup["filesize"];
                $output[] = $backup;
            }
        }
        return [
            "backups"=>$output,
            "size"=>$totalSize
        ];
    }

    private function findLastBackup(string $project, ?array $hostBackups)
    {
        $x = [
            "neverBackedUp"=>true
        ];

        if (empty($hostBackups)) {
            return $x;
        }

        $latestDate =  null;

        foreach ($hostBackups as $backup) {
            if ($project == $backup["project"]) {
                $x["neverBackedUp"] = false;
                if ((new \DateTime($backup["storedDateCreated"])) > $latestDate) {
                    $latestDate = (new \DateTime($backup["storedDateCreated"]));
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
                $schedules[$hostId][$instance["project"]] = $instance;
                unset($schedules[$hostId][$index]);
            }
        }
        return $schedules;
    }
}
