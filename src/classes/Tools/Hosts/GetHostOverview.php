<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Tools\Containers\GetHostsContainers;
use dhope0000\LXDClient\Tools\Hosts\GetResources;

class GetHostOverview
{
    public function __construct(
        GetDetails $getDetails,
        GetHostsContainers $getHostsContainers,
        GetResources $getResources
    ) {
        $this->getDetails = $getDetails;
        $this->getHostsContainers = $getHostsContainers;
        $this->getResources = $getResources;
    }

    public function get(int $hostId)
    {
        $header = $this->getDetails->getIpAndAlias($hostId);
        $containers = $this->getHostsContainers->getContainers($hostId);
        $sortedContainers = $this->sortContainersByState($containers);
        $containerStats = $this->calcContainerStats($containers);
        return [
            "header"=>$header,
            "containers"=>$sortedContainers,
            "containerStats"=>$containerStats,
            "resources"=>$this->getResources->getHostExtended($hostId)
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
