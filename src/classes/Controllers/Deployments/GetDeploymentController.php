<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetDeployment;
use Symfony\Component\Routing\Attribute\Route;

class GetDeploymentController
{
    public function __construct(
        private readonly GetDeployment $getDeployment
    ) {
    }

    #[Route(path: '/api/Deployments/GetDeploymentController/get', name: 'api_deployments_getdeploymentcontroller_get', methods: ['POST'])]
    public function get(int $userId, int $deploymentId)
    {
        return $this->getDeployment->get($userId, $deploymentId);
    }
}
