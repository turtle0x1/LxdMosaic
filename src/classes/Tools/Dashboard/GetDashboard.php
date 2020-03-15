<?php

namespace dhope0000\LXDClient\Tools\Dashboard;

use dhope0000\LXDClient\Tools\Clusters\GetAllClusters;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Analytics\GetLatestData;
use dhope0000\LXDClient\Tools\Hosts\GetResources;

class GetDashboard
{
    public function __construct(
        GetAllClusters $getAllClusters,
        HostList $hostList,
        GetLatestData $getLatestData,
        GetResources  $getResources
    ) {
        $this->getAllClusters = $getAllClusters;
        $this->hostList = $hostList;
        $this->getLatestData = $getLatestData;
        $this->getResources = $getResources;
    }

    public function get()
    {
        $clustersAndHosts = $this->getClustersAndStandaloneHosts();
        $stats = $this->getStatsFromClustersAndHosts($clustersAndHosts);
        $analyticsData = $this->getLatestData->get();

        return [
            "clustersAndHosts"=>$clustersAndHosts,
            "stats"=>$stats,
            "analyticsData"=>$analyticsData
        ];
    }

    private function getStatsFromClustersAndHosts(array $clustersAndHosts)
    {
        $memory = [
            "total"=>0,
            "used"=>0
        ];

        foreach ($clustersAndHosts["clusters"] as $cluster) {
            $memory["total"] += $cluster["stats"]["totalMemory"];
            $memory["used"] += $cluster["stats"]["usedMemory"];
        }

        foreach ($clustersAndHosts["standalone"] as $host) {
            if ((int)$host["Host_Online"] === 0) {
                continue;
            }
            $memory["total"] += $host["resources"]["memory"]["total"];
            $memory["used"] += $host["resources"]["memory"]["used"];
        }

        return [
            "memory"=>$memory
        ];
    }

    private function getClustersAndStandaloneHosts()
    {
        $clusters = $this->getAllClusters->get(false);

        $hostsInClusterGroups = [];

        foreach ($clusters as $cluster) {
            $hostsInClusterGroups = array_merge($hostsInClusterGroups, array_column($cluster["members"], "hostId"));
        }

        $standaloneHosts = $this->hostList->fetchHostsNotInList($hostsInClusterGroups);

        foreach ($standaloneHosts as $index => $host) {
            $standaloneHosts[$index]["resources"] = [];

            if ((int)$host["Host_Online"] === 0) {
                continue;
            }

            $info = $this->getResources->getHostExtended($host["Host_ID"]);
            $standaloneHosts[$index]["resources"] = $info;
        }

        return [
            "clusters"=>$clusters,
            "standalone"=>$standaloneHosts
        ];
    }
}
