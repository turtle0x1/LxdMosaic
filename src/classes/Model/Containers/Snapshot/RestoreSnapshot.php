<?php
namespace dhope0000\LXDClient\Model\Containers\Snapshot;

use dhope0000\LXDClient\Model\Client\LxdClient;

class RestoreSnapshot
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function restoreSnapshot($host, $container, $snapshotName)
    {
        $client = $this->lxdClient->getClientByUrl($host);
        return $client->containers->snapshots->restore($container, $snapshotName);
    }
}
