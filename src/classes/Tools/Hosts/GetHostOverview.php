<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Tools\Instances\GetHostsInstances;
use dhope0000\LXDClient\Tools\Hosts\GetResources;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class GetHostOverview
{
    public function __construct(
        GetHostsInstances $getHostsInstances,
        GetResources $getResources,
        HasExtension $hasExtension,
        FetchUserDetails $fetchUserDetails,
        FetchAllowedProjects $fetchAllowedProjects
    ) {
        $this->getHostsInstances = $getHostsInstances;
        $this->getResources = $getResources;
        $this->hasExtension = $hasExtension;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function get(int $userId, Host $host)
    {
        $containers = $this->getHostsInstances->getContainers($host);
        $sortedContainers = $this->sortContainersByState($containers);
        $containerStats = $this->calcContainerStats($containers);
        $supportsWarnings = $this->hasExtension->checkWithHost($host, LxdApiExtensions::WARNINGS);
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        $resources = $this->getResources->getHostExtended($host);

        if (!$isAdmin) {
            $resources["projects"] = $this->fetchAllowedProjects->fetchForUserHost($userId, $host->getHostId());
        }
        return [
            "header"=>$host,
            "containers"=>$sortedContainers,
            "containerStats"=>$containerStats,
            "supportsWarnings"=>$supportsWarnings
            "resources"=>$resources,
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
