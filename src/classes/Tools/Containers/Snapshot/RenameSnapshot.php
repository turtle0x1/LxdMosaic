<?php
namespace dhope0000\LXDClient\Tools\Containers\Snapshot;

use dhope0000\LXDClient\Model\Client\LxdClient;

class RenameSnapshot
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function rename(
        int $hostId,
        string $container,
        string $snapshotName,
        string $newSnapshotName
    ) {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->containers->snapshots->rename(
            $container,
            $snapshotName,
            $newSnapshotName,
            true
        );
    }
}
