<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class DeleteNetwork
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function delete(int $hostId, string $network)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->networks->remove($network);
    }
}
