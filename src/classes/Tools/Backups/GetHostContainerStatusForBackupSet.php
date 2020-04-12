<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Instances\GetHostsContainers;

class GetHostContainerStatusForBackupSet
{
    private $getHostsContainers;
    private $lxdClient;

    public function __construct(
        GetHostsContainers $getHostsContainers,
        LxdClient $lxdClient
    ) {
        $this->getHostsContainers = $getHostsContainers;
        $this->lxdClient = $lxdClient;
    }

    public function get(array $backups)
    {
        $hostsContainers = $this->getHostsContainers->getHostsContainers(true);

        $backupHostIds = array_unique(array_column($backups, "hostId"));

        $backupsByHostId = $this->createBackupsByHostIdStruct($backups);

        $missingBackups = [];

        foreach ($hostsContainers as $hostIdent => $host) {
            if (empty($host["containers"])) {
                continue;
            }

            $backupsToSearch = [];

            if (isset($backupsByHostId[$host["hostId"]])) {
                $backupsToSearch = $backupsByHostId[$host["hostId"]];
            }

            $containers = [];
            $seenContainerNames = [];

            foreach ($host["containers"] as $name => $container) {
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

            $host["containers"] = $containers;

            unset($host["hostInfo"]);

            $missingBackups[$hostIdent] = $host;
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
            "created"=>$container["info"]["created_at"]
        ];
    }
}
