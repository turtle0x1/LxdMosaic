<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Containers\GetHostsContainers;

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

            $containers = [];

            foreach ($host["containers"] as $name => $container) {
                $backupsToSearch = [];

                if (isset($backupsByHostId[$host["hostId"]])) {
                    $backupsToSearch = $backupsByHostId[$host["hostId"]];
                }

                $lastBackup = $this->findLastBackup($name, $backupsToSearch);
                $container = $this->extractContainerInfo($name, $container);

                $container["lastBackup"] = $lastBackup;
                $containers[] = $container;
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

    private function findLastBackup(string $container, ?array $hostBackups)
    {
        $x = [
            "neverBackedUp"=>true,
            "containerExists"=>true // This is for later when we offer restoring
                                    // backups for deleted containers
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
