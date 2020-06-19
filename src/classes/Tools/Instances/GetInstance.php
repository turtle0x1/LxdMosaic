<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Deployments\FetchDeployments;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Metrics\FetchMetrics;

class GetInstance
{
    private $hasExtension;

    public function __construct(
        FetchDeployments $fetchDeployments,
        HasExtension $hasExtension,
        FetchMetrics $fetchMetrics
    ) {
        $this->fetchDeployments = $fetchDeployments;
        $this->hasExtension = $hasExtension;
        $this->fetchMetrics = $fetchMetrics;
    }

    public function get(Host $host, string $instance)
    {
        $details = $host->instances->info($instance);
        $state = $host->instances->state($instance);
        $snapshots = $host->instances->snapshots->all($instance);

        $hostId = $host->getHostId();
        $deploymentDetails = $this->fetchDeployments->byHostContainer($hostId, $instance);
        $hostSupportsBackups = $this->hasExtension->checkWithHost($host, LxdApiExtensions::CONTAINER_BACKUP);
        $haveMetrics = (bool) count($this->fetchMetrics->fetchAllTypes($host->getHostId(), $instance));

        return [
            "details"=>$details,
            "state"=>$state,
            "haveMetrics"=>$haveMetrics,
            "snapshots"=>$snapshots,
            "deploymentDetails"=>$deploymentDetails,
            "backupsSupported"=>$hostSupportsBackups
        ];
    }
}
