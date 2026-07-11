<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetCloudConfigs;
use Symfony\Component\Routing\Attribute\Route;

class GetCloudsConfigController
{
    public function __construct(
        private readonly GetCloudConfigs $getCloudConfigs
    ) {
    }

    #[Route(path: '/api/Deployments/GetCloudsConfigController/get', name: 'Get deployment cloud configs', methods: ['POST'])]
    public function get(int $userId, int $deploymentId)
    {
        return $this->getCloudConfigs->getAll($userId, $deploymentId);
    }
}
