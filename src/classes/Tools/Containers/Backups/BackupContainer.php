<?php

namespace dhope0000\LXDClient\Tools\Containers\Backups;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Containers\Backups\StoreBackupLocally;
use dhope0000\LXDClient\Tools\Containers\Backups\DeleteRemoteBackup;

class BackupContainer
{
    public function __construct(
        LxdClient $lxdClient,
        StoreBackupLocally $storeBackupLocally,
        DeleteRemoteBackup $deleteRemoteBackup
    ) {
        $this->lxdClient = $lxdClient;
        $this->storeBackupLocally = $storeBackupLocally;
        $this->deleteRemoteBackup = $deleteRemoteBackup;
    }

    public function create(
        int $hostId,
        string $container,
        string $backup,
        $wait,
        bool $importAndDelete
    ) {
        if ($wait === "false") {
            $wait = false;
        }

        if ($importAndDelete == true) {
            $wait = true;
        }
        
        $client = $this->lxdClient->getANewClient($hostId);

        $response = $client->containers->backups->create($container, $backup, [], $wait);

        if ($importAndDelete) {
            if ($this->storeBackupLocally->store($hostId, $container, $backup)) {
                $this->deleteRemoteBackup->delete($hostId, $container, $backup);
            }
        }

        return $response;
    }
}
