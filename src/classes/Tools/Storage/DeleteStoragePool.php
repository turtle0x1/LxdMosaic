<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class DeleteStoragePool
{
    public function __construct(HostList $hostList, LxdClient $lxdClient)
    {
        $this->hostList = $hostList;
        $this->lxdClient = $lxdClient;
    }

    public function delete(int $hostId, string $poolName)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->storage->remove($poolName);
    }
}
