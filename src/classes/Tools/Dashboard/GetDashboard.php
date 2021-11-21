<?php

namespace dhope0000\LXDClient\Tools\Dashboard;

use dhope0000\LXDClient\Model\Users\Dashboard\FetchUserDashboards;
use dhope0000\LXDClient\Tools\Universe;
use dhope0000\LXDClient\Tools\User\GetUserProject;
use dhope0000\LXDClient\Tools\ProjectAnalytics\GetGraphableProjectAnalytics;

class GetDashboard
{
    public function __construct(
        FetchUserDashboards $fetchUserDashboards,
        Universe $universe,
        GetUserProject $getUserProject,
        GetGraphableProjectAnalytics $getGraphableProjectAnalytics
    ) {
        $this->fetchUserDashboards = $fetchUserDashboards;
        $this->universe = $universe;
        $this->getUserProject = $getUserProject;
        $this->getGraphableProjectAnalytics = $getGraphableProjectAnalytics;
    }

    public function get($userId, string $history = "-30 minutes")
    {
        $clustersAndHosts = $this->universe->getEntitiesUserHasAccesTo($userId);
        $dashboards = $this->fetchUserDashboards->fetchAll($userId);
        $projectGraphData = $this->getGraphableProjectAnalytics->getCurrent($userId, $history);

        return [
            "userDashboards"=>$dashboards,
            "clustersAndHosts"=>$clustersAndHosts,
            "projectsUsageGraphData"=>$projectGraphData["totals"]
        ];
    }
}
