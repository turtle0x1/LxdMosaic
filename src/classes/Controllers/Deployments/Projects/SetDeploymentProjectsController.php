<?php

namespace dhope0000\LXDClient\Controllers\Deployments\Projects;

use dhope0000\LXDClient\Tools\Deployments\Projects\SetDeploymentProjects;
use DI\Container;
use Symfony\Component\Routing\Attribute\Route;

class SetDeploymentProjectsController
{
    public function __construct(
        private readonly SetDeploymentProjects $setDeploymentProjects,
        private readonly Container $container
    ) {
    }

    #[Route(path: '/api/Deployments/Projects/SetDeploymentProjectsController/set', name: 'Set Deployment Projects', methods: ['POST'])]
    public function set(int $userId, int $deploymentId, array $newProjectsLayout)
    {
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", 'beginTransaction']);
        $this->setDeploymentProjects->set($userId, $deploymentId, $newProjectsLayout);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", 'commitTransaction']);
        return [
            'state' => 'success',
            'message' => 'Updated Projects',
        ];
    }
}
