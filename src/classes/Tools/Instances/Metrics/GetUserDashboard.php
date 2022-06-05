<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Model\Users\Dashboard\FetchUserDashboards;
use dhope0000\LXDClient\Model\Users\Dashboard\Graphs\FetchDashboardGraphs;
use dhope0000\LXDClient\Tools\Instances\Metrics\GetMetricsForContainer;

class GetUserDashboard
{
    private $fetchUserDashboards;
    private $fetchDashboardGraphs;
    private $getMetricsForContainer;
    
    public function __construct(
        FetchUserDashboards $fetchUserDashboards,
        FetchDashboardGraphs $fetchDashboardGraphs,
        GetMetricsForContainer $getMetricsForContainer
    ) {
        $this->fetchUserDashboards = $fetchUserDashboards;
        $this->fetchDashboardGraphs = $fetchDashboardGraphs;
        $this->getMetricsForContainer = $getMetricsForContainer;
    }

    public function get(int $userId, $dashboardId)
    {
        $dash = $this->fetchUserDashboards->fetchDashboard($dashboardId);

        if (empty($dash)) {
            throw new \Exception("Couldn't find dash", 1);
        }

        if ((int) $dash["userId"] !== $userId && (int)$dash["public"] !== 1) {
            throw new \Exception("No permision", 1);
        }

        $graphs = $this->fetchDashboardGraphs->fetchAll($dashboardId);

        $outputGraphs = [];

        foreach ($graphs as $graph) {
            $result = $this->getMetricsForContainer->get(
                $graph["hostId"],
                $graph["instance"],
                $graph["metricId"],
                $graph["filter"],
                $graph["range"]
            );

            $outputGraphs[] = array_merge([
                "graphId"=>$graph["graphId"],
                "graphName"=>$graph["graphName"]
            ], $result);
        }

        return [
            "header"=>$dash,
            "graphsData"=>$outputGraphs
        ];
    }
}
