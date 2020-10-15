<?php

namespace dhope0000\LXDClient\Tools\Dashboard;

use dhope0000\LXDClient\Model\Users\Projects\FetchUserProject;
use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;
use dhope0000\LXDClient\Tools\Analytics\GetLatestData;
use dhope0000\LXDClient\Model\Users\Dashboard\FetchUserDashboards;
use dhope0000\LXDClient\Tools\Universe;
use dhope0000\LXDClient\Tools\Hosts\GetResources;

class GetDashboard
{
    public function __construct(
        FetchUserProject $fetchUserProject,
        GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts,
        GetLatestData $getLatestData,
        FetchUserDashboards $fetchUserDashboards,
        Universe $universe,
        GetResources $getResources
    ) {
        $this->fetchUserProject = $fetchUserProject;
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
        $this->getLatestData = $getLatestData;
        $this->fetchUserDashboards = $fetchUserDashboards;
        $this->universe = $universe;
        $this->getResources = $getResources;
    }

    public function get($userId)
    {
        $clustersAndHosts = $this->universe->getEntitiesUserHasAccesTo($userId, "projects");
        $clustersAndHosts = $this->addCurrentProjects($userId, $clustersAndHosts);
        $stats = $this->getStatsFromClustersAndHosts($clustersAndHosts);
        $analyticsData = $this->getLatestData->get();
        $dashboards = $this->fetchUserDashboards->fetchAll($userId);

        return [
            "userDashboards"=>$dashboards,
            "clustersAndHosts"=>$clustersAndHosts,
            "stats"=>$stats,
            "analyticsData"=>$analyticsData
        ];
    }

    private function addCurrentProjects($userId, $clustersAndHosts)
    {
        foreach ($clustersAndHosts["clusters"] as $index => $cluster) {
            foreach ($cluster["members"] as $member) {
                $project = $this->fetchUserProject->fetchOrDefault($userId, $member->getHostId());
                $member->setCustomProp("currentProject", $project);
                $member->setCustomProp("resources", $this->getResources($member));
            }
        }
        foreach ($clustersAndHosts["standalone"]["members"] as $index => $member) {
            $project = $this->fetchUserProject->fetchOrDefault($userId, $member->getHostId());
            $member->setCustomProp("currentProject", $project);
            $member->setCustomProp("resources", $this->getResources($member));
        }
        return $clustersAndHosts;
    }

    private function getResources($member)
    {
        if ($member->hostOnline() == false) {
            return [];
        }
        $r = $this->getResources->getHostExtended($member);
        unset($r["projects"]);
        return $r;
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
            if (!$host->hostOnline()) {
                continue;
            }
            $memory["total"] += $host->getCustomProp("resources")["memory"]["total"];
            $memory["used"] += $host->getCustomProp("resources")["memory"]["used"];
        }

        return [
            "memory"=>$memory
        ];
    }
}
