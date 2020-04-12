<?php
namespace dhope0000\LXDClient\Tools\Instances\Snapshot;

use dhope0000\LXDClient\Model\Client\LxdClient;

class TakeSnapshot
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function takeSnapshot(int $hostId, string $instance, string $snapshotName)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->instances->snapshots->create($instance, $snapshotName, false, true);
    }
}
