<?php

namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Deployments\FetchDeployments;

class GetContainer
{
    public function __construct(LxdClient $lxdClient, FetchDeployments $fetchDeployments)
    {
        $this->client = $lxdClient;
        $this->fetchDeployments = $fetchDeployments;
    }

    public function get(string $hostId, string $container)
    {
        $client = $this->client->getANewClient($hostId);

        $details = $client->containers->info($container);
        $state = $client->containers->state($container);
        $snapshots = $client->containers->snapshots->all($container);
        $deploymentDetails = $this->fetchDeployments->byHostContainer($hostId, $container);

        return [
            "details"=>$details,
            "state"=>$state,
            "snapshots"=>$snapshots,
            "deploymentDetails"=>$deploymentDetails
        ];
    }
}
