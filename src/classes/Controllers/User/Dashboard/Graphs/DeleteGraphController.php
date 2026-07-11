<?php

namespace dhope0000\LXDClient\Controllers\User\Dashboard\Graphs;

use dhope0000\LXDClient\Tools\User\Dashboard\Graphs\DeleteGraph;
use Symfony\Component\Routing\Attribute\Route;

class DeleteGraphController
{
    public function __construct(
        private readonly DeleteGraph $deleteGraph
    ) {
    }

    #[Route(path: '/api/User/Dashboard/Graphs/DeleteGraphController/delete', name: 'Delete dashboard graph', methods: ['POST'])]
    public function delete(int $userId, int $graphId)
    {
        $this->deleteGraph->delete($userId, $graphId);
        return [
            'state' => 'success',
            'message' => 'Delete Graph',
        ];
    }
}
