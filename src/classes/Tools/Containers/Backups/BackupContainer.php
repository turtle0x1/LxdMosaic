<?php

namespace dhope0000\LXDClient\Tools\Containers\Backups;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Containers\Backups\StoreBackupLocally;

class BackupContainer
{
    private $lxdClient;

    private $storeBackupLocally;

    public function __construct(
        LxdClient $lxdClient,
        StoreBackupLocally $storeBackupLocally
    ) {
        $this->lxdClient = $lxdClient;
        $this->storeBackupLocally = $storeBackupLocally;
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
            $this->storeBackupLocally->store($hostId, $container, $backup, true);
        }

        return $response;
    }
}
