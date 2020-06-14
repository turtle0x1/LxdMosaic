<?php

namespace dhope0000\LXDClient\Tools\User\Dashboard\Graphs;

use dhope0000\LXDClient\Model\Users\Dashboard\Graphs\DeleteDashboardGraph;

class DeleteGraph
{
    public function __construct(DeleteDashboardGraph $deleteDashboardGraph)
    {
        $this->deleteDashboardGraph = $deleteDashboardGraph;
    }

    public function delete(int $userId, int $graphId)
    {
        //TODO Authenticate
        $this->deleteDashboardGraph->delete($graphId);
    }
}
