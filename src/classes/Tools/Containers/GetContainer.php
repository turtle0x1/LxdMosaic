<?php

namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class GetContainer
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function get(string $host, string $containerName)
    {
        $client = $this->client->getClientByUrl($host);
        $details = $client->containers->info($containerName);
        $state = $client->containers->state($containerName);
        $snapshots = $client->containers->snapshots->all($containerName);
        return [
            "details"=>$details,
            "state"=>$state,
            "snapshots"=>$snapshots
        ];
    }
}
