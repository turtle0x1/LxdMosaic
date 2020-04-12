<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Deployments\FetchDeployments;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;

class GetInstance
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

    public function get(string $hostId, string $instance)
    {
        $client = $this->client->getANewClient($hostId);

        $details = $client->instances->info($instance);
        $state = $client->instances->state($instance);
        $snapshots = $client->instances->snapshots->all($instance);
        $deploymentDetails = $this->fetchDeployments->byHostContainer($hostId, $instance);
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
