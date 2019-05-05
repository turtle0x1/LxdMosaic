<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class CreateNetwork
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function create(array $hostIds, string $name, string $description = "", array $config = [])
    {
        foreach($hostIds as $hostId){
            $client = $this->lxdClient->getANewClient($hostId);
            $client->networks->create($name, $description, $config);
        }

        return true;
    }
}
