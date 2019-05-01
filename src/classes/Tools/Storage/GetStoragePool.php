<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class GetStoragePool
{
    public function __construct(HostList $hostList, LxdClient $lxdClient)
    {
        $this->hostList = $hostList;
        $this->lxdClient = $lxdClient;
    }

    public function get(int $hostId, string $poolName)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        $info = $client->storage->info($poolName);
        $info["resources"] = $client->storage->resources->info($poolName);
        return $info;
    }
}
