<?php

namespace dhope0000\LXDClient\Tools\Containers\Backups;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Containers\Backups\StoreBackupLocally;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;

class BackupContainer
{
    private $lxdClient;

    private $storeBackupLocally;

    private $hasExtension;

    public function __construct(
        LxdClient $lxdClient,
        StoreBackupLocally $storeBackupLocally,
        HasExtension $hasExtension
    ) {
        $this->lxdClient = $lxdClient;
        $this->storeBackupLocally = $storeBackupLocally;
        $this->hasExtension = $hasExtension;
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

        if ($this->hasExtension->checkWithClient($client, LxdApiExtensions::CONTAINER_BACKUP) !== true) {
            throw new \Exception("Host doesn't support backups", 1);
        }

        $response = $client->containers->backups->create($container, $backup, [], $wait);

        if ($importAndDelete) {
            $this->storeBackupLocally->store($hostId, $container, $backup, true);
        }

        return $response;
    }
}
