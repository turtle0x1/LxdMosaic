<?php

namespace dhope0000\LXDClient\Tools\Dashboard;

use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;
use dhope0000\LXDClient\Tools\Analytics\GetLatestData;

class GetDashboard
{
    public function __construct(
        GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts,
        GetLatestData $getLatestData
    ) {
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
        $this->getLatestData = $getLatestData;
    }

    public function get()
    {
        $clustersAndHosts = $this->getClustersAndStandaloneHosts->get();
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

        foreach ($clustersAndHosts["standalone"]["members"] as $host) {
            if ((int)$host["hostOnline"] === 0) {
                continue;
            }
            $memory["total"] += $host["resources"]["memory"]["total"];
            $memory["used"] += $host["resources"]["memory"]["used"];
        }

        return [
            "memory"=>$memory
        ];
    }
}
