<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\DeleteDeployment;
use Symfony\Component\Routing\Attribute\Route;

class DeleteDeploymentController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteDeployment $deleteDeployment
    ) {
    }

    #[Route(path: '/api/Deployments/DeleteDeploymentController/delete', name: 'Delete Deployment', methods: ['POST'])]
    public function delete(int $userId, int $deploymentId)
    {
        $this->deleteDeployment->delete($userId, $deploymentId);
        return [
            'state' => 'success',
            'message' => 'Deleted deployment',
        ];
    }
}
