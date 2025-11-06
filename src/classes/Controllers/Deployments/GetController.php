<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetDeployments;
use Symfony\Component\Routing\Attribute\Route;

class GetController
{
    public function __construct(
        private readonly GetDeployments $getDeployments
    ) {
    }

    #[Route(path: '/api/Deployments/GetController/getAll', name: 'api_deployments_getcontroller_getall', methods: ['POST'])]
    public function getAll(int $userId)
    {
        return $this->getDeployments->getAll($userId);
    }
}
