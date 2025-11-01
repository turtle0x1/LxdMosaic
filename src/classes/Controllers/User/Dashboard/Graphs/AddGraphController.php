<?php

namespace dhope0000\LXDClient\Controllers\User\Dashboard\Graphs;

use dhope0000\LXDClient\Tools\User\Dashboard\Graphs\AddGraph;
use Symfony\Component\Routing\Annotation\Route;

class AddGraphController
{
    private $addGraph;

    public function __construct(AddGraph $addGraph)
    {
        $this->addGraph = $addGraph;
    }

    /**
     * @Route("/api/User/Dashboard/Graphs/AddGraphController/add", name="api_user_dashboard_graphs_addgraphcontroller_add", methods={"POST"})
     */
    public function add(
        int $userId,
        int $dashboardId,
        string $name,
        int $hostId,
        string $instance,
        int $metricId,
        string $filter,
        string  $range
    ) {
        $this->addGraph->add(
            $userId,
            $dashboardId,
            $name,
            $hostId,
            $instance,
            $metricId,
            $filter,
            $range
        );
        return ["state"=>"success", "message"=>"Added Graph"];
    }
}
