<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Constants\StateConstants;
use dhope0000\LXDClient\Tools\Deployments\ChangeDeploymentState;
use Symfony\Component\Routing\Attribute\Route;

class StartDeploymentController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly ChangeDeploymentState $changeDeploymentState
    ) {
    }

    #[Route(path: '/api/Deployments/StartDeploymentController/start', name: 'Start Deployment', methods: ['POST'])]
    public function start(int $userId, int $deploymentId)
    {
        $this->changeDeploymentState->change($userId, $deploymentId, StateConstants::START);
        return [
            'state' => 'success',
            'message' => 'Deployment started',
        ];
    }
}
