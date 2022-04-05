<?php

namespace dhope0000\LXDClient\Controllers\User\Dashboard\Graphs;

use dhope0000\LXDClient\Tools\User\Dashboard\Graphs\DeleteGraph;
use Symfony\Component\Routing\Annotation\Route;

class DeleteGraphController
{
    private $deleteGraph;

    public function __construct(DeleteGraph $deleteGraph)
    {
        $this->deleteGraph = $deleteGraph;
    }
    /**
     * @Route("/api/User/Dashboard/Graphs/DeleteGraphController/delete", methods={"POST"}, name="Delete dashbaord from user dashboard", options={"rbac" = "user.dashboards.delete"})
     */
    public function delete(int $userId, int $graphId)
    {
        $this->deleteGraph->delete($userId, $graphId);
        return ["state"=>"success", "message"=>"Delete Graph"];
    }
}
