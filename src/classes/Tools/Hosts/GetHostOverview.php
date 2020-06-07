<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Tools\Instances\GetHostsInstances;
use dhope0000\LXDClient\Tools\Hosts\GetResources;
use dhope0000\LXDClient\Objects\Host;

class GetHostOverview
{
    public function __construct(
        GetHostsInstances $getHostsInstances,
        GetResources $getResources
    ) {
        $this->getHostsInstances = $getHostsInstances;
        $this->getResources = $getResources;
    }

    public function get(Host $host)
    {
        $containers = $this->getHostsInstances->getContainers($host->getHostId());
        $sortedContainers = $this->sortContainersByState($containers);
        $containerStats = $this->calcContainerStats($containers);
        return [
            "header"=>$host,
            "containers"=>$sortedContainers,
            "containerStats"=>$containerStats,
            "resources"=>$this->getResources->getHostExtended($host->getHostId())
        ];
    }

    private function sortContainersByState(array $containers)
    {
        $output = [];
        foreach ($containers as $containerName => $container) {
            if (!isset($output[$container["state"]["status"]])) {
                $output[$container["state"]["status"]] = [];
            }

            $output[$container["state"]["status"]][$containerName] = $container;
        }
        return $output;
    }

    private function calcContainerStats(array $containers)
    {
        $stats = [
            "online"=>0,
            "offline"=>0
        ];
        foreach ($containers as $container) {
            if ($container["state"]["status_code"] == 103) {
                $stats["online"]++;
            } else {
                $stats["offline"]++;
            }
        }
        return $stats;
    }
}
