<?php
namespace dhope0000\LXDClient\Model\Containers\Snapshot;

use dhope0000\LXDClient\Model\Client\LxdClient;

class TakeSnapshot
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function takeSnapshot($host, $container, $snapshotName)
    {
        $client = $this->lxdClient->getClientByUrl($host);
        return $client->containers->snapshots->create($container, $snapshotName, false, true);
    }
}
