<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class CreateStoragePool
{
    public function __construct(HostList $hostList, LxdClient $lxdClient)
    {
        $this->hostList = $hostList;
        $this->lxdClient = $lxdClient;
    }

    public function create(array $hosts, string $name, string $driver, array $config)
    {
        foreach($hosts as $hostId){
            $client = $this->lxdClient->getANewClient($hostId);
            $client->storage->create($name, $driver, $config);
        }
        return true;
    }
}
