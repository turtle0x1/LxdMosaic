<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Backups\FetchBackup;
use dhope0000\LXDClient\Model\Client\LxdClient;

class RestoreBackup
{
    private $fetchBackup;
    private $lxdClient;

    public function __construct(FetchBackup $fetchBackup, LxdClient $lxdClient)
    {
        $this->fetchBackup = $fetchBackup;
        $this->lxdClient = $lxdClient;
    }

    public function restore(int $backupId, int $targetHost)
    {
        $backup = $this->fetchBackup->fetch($backupId);

        $client = $this->lxdClient->getANewClient($targetHost);

        return $client->instances->create("", [
            "source"=>"backup",
            "file"=>file_get_contents($backup["localPath"])
        ], true);
    }
}
