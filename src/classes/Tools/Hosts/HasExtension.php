<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Client\LxdClient;
use Opensaucesystems\Lxd\Client;

class HasExtension
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function hasWithHostId(int $hostId, $extension)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $this->checkWithClient($client, $extension);
    }


    public function checkWithClient(Client $client, $extension)
    {
        $extensions = $client->host->info()["api_extensions"];
        return in_array($extension, $extensions);
    }
}
