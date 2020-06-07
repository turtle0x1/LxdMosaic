<?php

namespace dhope0000\LXDClient\Tools\Instances\Backups;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\FetchInstanceBackups;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;

class GetInstanceBackups
{
    private $lxdClient;
    private $fetchInstanceBackups;
    private $hasExtension;

    public function __construct(
        LxdClient $lxdClient,
        FetchInstanceBackups $fetchInstanceBackups,
        HasExtension $hasExtension
    ) {
        $this->lxdClient = $lxdClient;
        $this->fetchInstanceBackups = $fetchInstanceBackups;
        $this->hasExtension = $hasExtension;
    }

    public function get(int $hostId, string $instance)
    {
        $client = $this->lxdClient->getANewClient($hostId);

        if ($this->hasExtension->checkWithClient($client, LxdApiExtensions::CONTAINER_BACKUP) !== true) {
            throw new \Exception("Host doesn't support backups", 1);
        }

        $localBackups = $this->fetchInstanceBackups->fetchAll($hostId, $instance);

        $remoteBackups = $this->getRemoteBackups($client, $instance, $localBackups);

        return [
            "localBackups"=>$localBackups,
            "remoteBackups"=>$remoteBackups
        ];
    }


    private function getRemoteBackups($client, string $instance, array $localBackups)
    {
        $backups = $client->instances->backups->all($instance);

        foreach ($backups as $index => $backupName) {
            $info = $client->instances->backups->info($instance, $backupName);

            $info["storedLocally"] = $this->backupDownloadedLocally($localBackups, $backupName, $info["created_at"]);
            $backups[$index] = $info;
        }
        usort($backups, function ($a, $b) {
            $t1 = strtotime($a["created_at"]);
            $t2 = strtotime($b["created_at"]);
            return $t2 - $t1;
        });
        return $backups;
    }

    private function backupDownloadedLocally($localBackups, string $name, $created)
    {
        foreach ($localBackups as $backup) {
            if ($backup["backupName"] == $name && new \DateTime($created) == new \DateTime($backup["dateCreated"])) {
                return true;
            }
        }

        return false;
    }
}
