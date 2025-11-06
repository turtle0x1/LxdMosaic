<?php

namespace dhope0000\LXDClient\Controllers\Deployments\Projects;

use dhope0000\LXDClient\Model\Deployments\Projects\FetchDeploymentProjects;
use dhope0000\LXDClient\Tools\Deployments\Authorise\AuthoriseDeploymentAccess;
use Symfony\Component\Routing\Attribute\Route;

class GetDeploymentProjectsController
{
    public function __construct(
        private readonly AuthoriseDeploymentAccess $authoriseDeploymentAccess,
        private readonly FetchDeploymentProjects $fetchDeploymentProjects
    ) {
    }

    #[Route(path: '/api/Deployments/Projects/GetDeploymentProjectsController/get', name: 'Get Deployment Projects', methods: ['POST'])]
    public function get(int $userId, int $deploymentId)
    {
        $this->authoriseDeploymentAccess->authorise($userId, $deploymentId);
        return $this->fetchDeploymentProjects->fetchAll($deploymentId);
    }
}
