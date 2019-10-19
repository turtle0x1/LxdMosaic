<?php

namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\Backups\Containers\FetchContainerBackups;

class GetContainer
{
    public function __construct(LxdClient $lxdClient, FetchContainerBackups $fetchContainerBackups)
    {
        $this->client = $lxdClient;
        $this->fetchContainerBackups = $fetchContainerBackups;
    }

    public function get(string $hostId, string $containerName)
    {
        $client = $this->client->getANewClient($hostId);
        $details = $client->containers->info($containerName);
        $state = $client->containers->state($containerName);
        $snapshots = $client->containers->snapshots->all($containerName);
        $localBackups = $this->fetchContainerBackups->fetchAll($hostId, $containerName);
        $remoteBackups = $client->containers->backups->all($containerName);

        return [
            "details"=>$details,
            "state"=>$state,
            "snapshots"=>$snapshots,
            "backups"=>[
                "localBackups"=>$localBackups,
                "remoteBackups"=>$remoteBackups
            ]
        ];
    }
}
