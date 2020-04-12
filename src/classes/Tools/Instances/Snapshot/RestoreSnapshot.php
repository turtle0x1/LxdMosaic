<?php
namespace dhope0000\LXDClient\Tools\Instances\Snapshot;

use dhope0000\LXDClient\Model\Client\LxdClient;

class RestoreSnapshot
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function restoreSnapshot(int $hostId, string $instance, string $snapshotName)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->instances->snapshots->restore($instance, $snapshotName, true);
    }
}
