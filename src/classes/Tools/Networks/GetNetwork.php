<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class GetNetwork
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function get(int $hostId, string $network)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->networks->info($network);
    }
}
