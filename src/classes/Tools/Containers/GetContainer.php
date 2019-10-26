<?php

namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Deployments\FetchDeployments;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;

class GetContainer
{
    private $hasExtension;

    public function __construct(
        LxdClient $lxdClient,
        FetchDeployments $fetchDeployments,
        HasExtension $hasExtension
    ) {
        $this->client = $lxdClient;
        $this->fetchDeployments = $fetchDeployments;
        $this->hasExtension = $hasExtension;
    }

    public function get(string $hostId, string $container)
    {
        $client = $this->client->getANewClient($hostId);

        $details = $client->containers->info($container);
        $state = $client->containers->state($container);
        $snapshots = $client->containers->snapshots->all($container);
        $deploymentDetails = $this->fetchDeployments->byHostContainer($hostId, $container);
        $hostSupportsBackups = $this->hasExtension->checkWithClient($client, LxdApiExtensions::CONTAINER_BACKUP);

        return [
            "details"=>$details,
            "state"=>$state,
            "snapshots"=>$snapshots,
            "deploymentDetails"=>$deploymentDetails,
            "backupsSupported"=>$hostSupportsBackups
        ];
    }
}
