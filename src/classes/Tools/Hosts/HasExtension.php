<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Client\LxdClient;
use Opensaucesystems\Lxd\Client;

class HasExtension
{
    private $hostCache = [];

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
        $hostUrl = $client->getUrl();
        $info = isset($this->hostCache[$hostUrl]) ? $this->hostCache[$hostUrl] : $client->host->info();
        $this->hostCache[$hostUrl] = $info;
        return in_array($extension, $info["api_extensions"]);
    }
}
