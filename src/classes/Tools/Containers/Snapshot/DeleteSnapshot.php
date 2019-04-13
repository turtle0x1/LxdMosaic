<?php
namespace dhope0000\LXDClient\Tools\Containers\Snapshot;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteSnapshot
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function deleteSnapshot(int $hostId, string $container, string $snapshotName)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->containers->snapshots->remove($container, $snapshotName);
    }
}
