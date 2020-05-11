<?php

namespace dhope0000\LXDClient\Tools\Dashboard;

use dhope0000\LXDClient\Model\Users\Projects\FetchUserProject;
use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;
use dhope0000\LXDClient\Tools\Analytics\GetLatestData;

class GetDashboard
{
    public function __construct(
        FetchUserProject $fetchUserProject,
        GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts,
        GetLatestData $getLatestData
    ) {
        $this->fetchUserProject = $fetchUserProject;
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
        $this->getLatestData = $getLatestData;
    }

    public function get($userId)
    {
        $clustersAndHosts = $this->getClustersAndStandaloneHosts->get();
        $clustersAndHosts = $this->addCurrentProjects($userId, $clustersAndHosts);
        $stats = $this->getStatsFromClustersAndHosts($clustersAndHosts);
        $analyticsData = $this->getLatestData->get();

        return [
            "clustersAndHosts"=>$clustersAndHosts,
            "stats"=>$stats,
            "analyticsData"=>$analyticsData
        ];
    }

    private function addCurrentProjects($userId, $clustersAndHosts)
    {
        foreach ($clustersAndHosts["clusters"] as $index => $cluster) {
            foreach ($cluster["members"] as $memIndex => $member) {
                $project = $this->fetchUserProject->fetchOrDefault($userId, $member["hostId"]);
                $clustersAndHosts["clusters"][$index]["members"][$memIndex]["currentProject"] = $project;
            }
        }
        foreach ($clustersAndHosts["standalone"]["members"] as $index => $member) {
            $project = $this->fetchUserProject->fetchOrDefault($userId, $member["hostId"]);
            $clustersAndHosts["standalone"]["members"][$index]["currentProject"] = $project;
        }
        return $clustersAndHosts;
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
