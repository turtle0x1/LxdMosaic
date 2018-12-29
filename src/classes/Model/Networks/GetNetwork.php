<?php
namespace dhope0000\LXDClient\Model\Networks;

use dhope0000\LXDClient\Model\Client\LxdClient;

class GetNetwork
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function getByName(string $host, string $networkName)
    {
        $client = $this->client->getClientByUrl($host);
        return [
            "config"=>$client->networks->info($networkName),
            "state"=>$client->networks->state($networkName)
        ];
    }
}
