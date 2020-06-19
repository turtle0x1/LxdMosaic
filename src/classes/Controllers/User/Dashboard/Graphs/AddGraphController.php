<?php

namespace dhope0000\LXDClient\Controllers\User\Dashboard\Graphs;

use dhope0000\LXDClient\Tools\User\Dashboard\Graphs\AddGraph;

class AddGraphController
{
    private $addGraph;

    public function __construct(AddGraph $addGraph)
    {
        $this->addGraph = $addGraph;
    }

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
