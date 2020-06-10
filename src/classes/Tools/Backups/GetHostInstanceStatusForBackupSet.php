<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Instances\GetHostsInstances;

class GetHostInstanceStatusForBackupSet
{
    private $getHostsInstances;
    private $lxdClient;

    public function __construct(
        GetHostsInstances $getHostsInstances,
        LxdClient $lxdClient
    ) {
        $this->getHostsInstances = $getHostsInstances;
        $this->lxdClient = $lxdClient;
    }

    public function get(array $backups)
    {
        $hostsContainers = $this->getHostsInstances->getAll(true);

        $backupHostIds = array_unique(array_column($backups, "hostId"));

        $backupsByHostId = $this->createBackupsByHostIdStruct($backups);

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

                $container["containerExists"] = true;
                $container["lastBackup"] = $lastBackup;
                $container["allBackups"] = $allBackups;

                $containers[] = $container;
                $seenContainerNames[] = $name;
            }

            foreach ($backupsToSearch as $backup) {
                if (!in_array($backup["container"], $seenContainerNames)) {
                    $containers[] = [
                        "name"=>$backup["container"],
                        "containerExists"=>false,
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

    private function extractContainerInfo(string $name, array $container)
    {
        return [
            "name"=>$name,
            "created"=>$container["created_at"]
        ];
    }
}
