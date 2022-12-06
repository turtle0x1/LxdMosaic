<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\FetchBackupSchedules;
use dhope0000\LXDClient\Tools\Universe;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;
use dhope0000\LXDClient\Objects\Host;

class GetHostInstanceStatusForBackupSet
{
    private FetchBackupSchedules $fetchBackupSchedules;
    private Universe $universe;
    private HasExtension $hasExtension;

    public function __construct(
        FetchBackupSchedules $fetchBackupSchedules,
        HasExtension $hasExtension,
        Universe $universe
    ) {
        $this->fetchBackupSchedules = $fetchBackupSchedules;
        $this->universe = $universe;
        $this->hasExtension = $hasExtension;
    }

    public function get(int $userId, array $backups)
    {
        $clustersAndStandalone = $this->universe->getEntitiesUserHasAccesTo($userId, "instances");

        $backupsByHostId = $this->createBackupsByHostIdStruct($backups);

        $groupedSchedule = $this->groupSchedules($this->fetchBackupSchedules->fetchActiveSchedsGroupedByHostId());

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

    private function addDetailsToHost(Host $host, array $backupsByHostId, array $groupedSchedule)
    {
        $backupsToSearch = [];

        if (isset($backupsByHostId[$host->getHostId()])) {
            $backupsToSearch = $backupsByHostId[$host->getHostId()];
        }

        $containers = [];
        $seenContainerNames = [];

        $currentProject = $host->callCLientMethod("getProject");

        foreach ($host->getCustomProp("instances") as $name) {
            $lastBackup = $this->findLastBackup($currentProject, $name, $backupsToSearch);
            $container = ["name"=>$name];
            $allBackups = $this->findAllBackupsandTotalSize($name, $backupsToSearch);

            $scheduleString = "";
            $scheduleRetention = "";
            $strategyName = "";

            if (isset($groupedSchedule[$host->getHostId()][$name])) {
                $scheduleString = $groupedSchedule[$host->getHostId()][$name]["scheduleString"];
                $scheduleRetention = $groupedSchedule[$host->getHostId()][$name]["scheduleRetention"];
                $strategyName = $groupedSchedule[$host->getHostId()][$name]["strategyName"];
            }

            $container["containerExists"] = true;
            $container["lastBackup"] = $lastBackup;
            $container["totalSize"] = $allBackups["size"];
            $container["allBackups"] = $allBackups["backups"];
            $container["scheduleString"] = $scheduleString;
            $container["scheduleRetention"] = $scheduleRetention;
            $container["strategyName"] = $strategyName;

            $containers[] = $container;

            if (!isset($seenContainerNames[$currentProject])) {
                $seenContainerNames[$currentProject] = [];
            }

            $seenContainerNames[$currentProject][] = $name;
        }

        foreach ($backupsToSearch as $backup) {
            if (!isset($seenContainerNames[$backup["project"]]) && $backup["project"] !== $currentProject) {
                continue;
            }

            if (!isset($seenContainerNames[$backup["project"]])) {
                $seenContainerNames[$backup["project"]] = [];
            }

            if (!in_array($backup["container"], $seenContainerNames[$backup["project"]])) {
                $allBackups = $this->findAllBackupsandTotalSize($backup["container"], $backupsToSearch);

                $containers[] = [
                    "name"=>$backup["container"],
                    "containerExists"=>false,
                    "scheduleString"=>"N/A",
                    "lastBackup"=>$this->findLastBackup($backup["project"], $backup["container"], $backupsToSearch),
                    "allBackups"=>$allBackups["backups"],
                    "totalSize"=>$allBackups["size"],
                    "scheduleRetention"=>"",
                    "strategyName"=>""
                ];
                $seenContainerNames[$backup["project"]][] = $backup["container"];
            }
        }


        $supportsBackups = $this->hasExtension->checkWithHost($host, LxdApiExtensions::CONTAINER_BACKUP);
        $host->setCustomProp("supportsBackups", $supportsBackups);
        $host->setCustomProp("containers", $containers);
        $host->removeCustomProp("hostInfo");
        $host->removeCustomProp("instances");

        return $host;
    }

    private function createBackupsByHostIdStruct(array $backups) :array
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

    private function findAllBackupsandTotalSize(string $container, array $hostBackups) :array
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

    private function findLastBackup(string $project, string $container, ?array $hostBackups) :array
    {
        $x = [
            "neverBackedUp"=>true
        ];

        if (empty($hostBackups)) {
            return $x;
        }

        $latestDate =  null;

        foreach ($hostBackups as $backup) {
            if ($project == $backup["project"] && $backup["container"] == $container) {
                $x["neverBackedUp"] = false;
                if ((new \DateTime($backup["backupDateCreated"])) > $latestDate) {
                    $latestDate = (new \DateTime($backup["backupDateCreated"]));
                    $x = array_merge($x, $backup);
                }
            }
        }
        return $x;
    }

    private function groupSchedules(array $schedules) :array
    {
        foreach ($schedules as $hostId => $instances) {
            foreach ($instances as $index => $instance) {
                $schedules[$hostId][$instance["instance"]] = $instance;
                unset($schedules[$hostId][$index]);
            }
        }
        return $schedules;
    }
}
