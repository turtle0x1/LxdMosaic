<?php

namespace dhope0000\LXDClient\Tools\Dashboard;

use dhope0000\LXDClient\Model\Users\Dashboard\FetchUserDashboards;
use dhope0000\LXDClient\Tools\ProjectAnalytics\GetGraphableProjectAnalytics;

class GetDashboard
{
    public function __construct(
        FetchUserDashboards $fetchUserDashboards,
        GetGraphableProjectAnalytics $getGraphableProjectAnalytics
    ) {
        $this->fetchUserDashboards = $fetchUserDashboards;
        $this->getGraphableProjectAnalytics = $getGraphableProjectAnalytics;
    }

    public function get($userId, string $history = "-30 minutes")
    {
        $dashboards = $this->fetchUserDashboards->fetchAll($userId);
        $projectGraphData = $this->getGraphableProjectAnalytics->getCurrent($userId, $history);

        return [
            "userDashboards"=>$dashboards,
            "projectsUsageGraphData"=>$projectGraphData["totals"]
        ];
    }
}
