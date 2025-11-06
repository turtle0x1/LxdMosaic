<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\CreateDeployment;
use Symfony\Component\Routing\Attribute\Route;

class CreateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly CreateDeployment $createDeployment
    ) {
    }

    #[Route(path: '/api/Deployments/CreateController/create', name: 'Create Deployment', methods: ['POST'])]
    public function create(string $name, array $cloudConfigs)
    {
        $deploymentId = $this->createDeployment->create($name, $cloudConfigs);
        return [
            'state' => 'success',
            'message' => 'Created deployment',
            'deploymentId' => $deploymentId,
        ];
    }
}
