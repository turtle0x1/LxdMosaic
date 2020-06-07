<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Deployments\FetchDeployments;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;
use dhope0000\LXDClient\Objects\Host;

class GetInstance
{
    private $hasExtension;

    public function __construct(
        FetchDeployments $fetchDeployments,
        HasExtension $hasExtension
    ) {
        $this->fetchDeployments = $fetchDeployments;
        $this->hasExtension = $hasExtension;
    }

    public function get(Host $host, string $instance)
    {
        $details = $host->instances->info($instance);
        $state = $host->instances->state($instance);
        $snapshots = $host->instances->snapshots->all($instance);

        $hostId = $host->getHostId();
        $deploymentDetails = $this->fetchDeployments->byHostContainer($hostId, $instance);
        $hostSupportsBackups = $this->hasExtension->hasWithHostId($hostId, LxdApiExtensions::CONTAINER_BACKUP);

        return [
            "details"=>$details,
            "state"=>$state,
            "snapshots"=>$snapshots,
            "deploymentDetails"=>$deploymentDetails,
            "backupsSupported"=>$hostSupportsBackups
        ];
    }
}
