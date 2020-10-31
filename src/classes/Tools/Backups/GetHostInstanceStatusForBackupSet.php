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
        // Cache the hosts current active project because its cheaper than
        // recalculating it later
        $hostsProject = [];

        foreach ($hostsContainers as $host) {
            $backupsToSearch = [];

            if (isset($backupsByHostId[$host->getHostId()])) {
                $backupsToSearch = $backupsByHostId[$host->getHostId()];
            }

            $containers = [];
            $seenContainerNames = [];

            $currentProject = $host->callCLientMethod("getProject");
            $hostsProject[$host->getHostId()] = $currentProject;

            foreach ($host->getCustomProp("containers") as $name => $container) {
                $lastBackup = $this->findLastBackup($currentProject, $name, $backupsToSearch);
                $container = $this->extractContainerInfo($name, $container);
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
                if (!isset($seenContainerNames[$backup["project"]]) && $backup["project"] !== $hostsProject[$backup["hostId"]]) {
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

    private function findLastBackup(string $project, string $container, ?array $hostBackups)
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
