<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Deployments\FetchDeployments;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Metrics\FetchMetrics;
use dhope0000\LXDClient\Tools\Instances\Devices\Proxy\GetAllInstanceProxies;

class GetInstance
{
    private $hasExtension;
    private $getAllInstanceProxies;

    public function __construct(
        FetchDeployments $fetchDeployments,
        HasExtension $hasExtension,
        FetchMetrics $fetchMetrics,
        GetAllInstanceProxies $getAllInstanceProxies
    ) {
        $this->fetchDeployments = $fetchDeployments;
        $this->hasExtension = $hasExtension;
        $this->fetchMetrics = $fetchMetrics;
        $this->getAllInstanceProxies = $getAllInstanceProxies;
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

        $totalMemory = $host->resources->info()["memory"]["total"];
        $memorySource = "Available On Host";

        $proxyDevices = $this->getAllInstanceProxies->get($details["expanded_devices"]);

        if (isset($details["config"]["limits.memory"])) {
            $memorySource = "Instance Limit";
            $totalMemory = $details["config"]["limits.memory"];
        }

        foreach ($details["expanded_devices"] as $name => &$device) {
            if ($device["type"] == "disk" && isset($device["pool"])) {
                $results = $host->storage->resources->info($device["pool"]);
                $state["disk"][$name]["poolSize"] = $results["space"]["total"];
            }
        }

        return [
            "details"=>$details,
            "state"=>$state,
            "haveMetrics"=>$haveMetrics,
            "snapshots"=>$snapshots,
            "deploymentDetails"=>$deploymentDetails,
            "backupsSupported"=>$hostSupportsBackups,
            "project"=>$host->callClientMethod("getProject"),
            "proxyDevices"=>$proxyDevices,
            "totalMemory"=>[
                "source"=>$memorySource,
                "total"=>$totalMemory
            ]
        ];
    }
}
