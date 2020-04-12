<?php
namespace dhope0000\LXDClient\Tools\Instances\Snapshot;

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
        return $client->instances->snapshots->rename(
            $container,
            $snapshotName,
            $newSnapshotName,
            true
        );
    }
}
