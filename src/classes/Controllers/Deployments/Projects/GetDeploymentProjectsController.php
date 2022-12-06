<?php

namespace dhope0000\LXDClient\Controllers\Deployments\Projects;

use dhope0000\LXDClient\Tools\Deployments\Authorise\AuthoriseDeploymentAccess;
use dhope0000\LXDClient\Model\Deployments\Projects\FetchDeploymentProjects;
use Symfony\Component\Routing\Annotation\Route;

class GetDeploymentProjectsController
{
    private AuthoriseDeploymentAccess $authoriseDeploymentAccess;
    private FetchDeploymentProjects $fetchDeploymentProjects;

    public function __construct(AuthoriseDeploymentAccess $authoriseDeploymentAccess, FetchDeploymentProjects $fetchDeploymentProjects)
    {
        $this->authoriseDeploymentAccess = $authoriseDeploymentAccess;
        $this->fetchDeploymentProjects = $fetchDeploymentProjects;
    }
    /**
     * @Route("", name="Get Deployment Projects")
     */
    public function get(int $userId, int $deploymentId) :array
    {
        $this->authoriseDeploymentAccess->authorise($userId, $deploymentId);
        return $this->fetchDeploymentProjects->fetchAll($deploymentId);
    }
}
