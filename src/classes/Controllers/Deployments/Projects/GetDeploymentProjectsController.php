<?php

namespace dhope0000\LXDClient\Controllers\Deployments\Projects;

use dhope0000\LXDClient\Tools\Deployments\Authorise\AuthoriseDeploymentAccess;
use dhope0000\LXDClient\Model\Deployments\Projects\FetchDeploymentProjects;
use Symfony\Component\Routing\Annotation\Route;

class GetDeploymentProjectsController
{
    private $authoriseDeploymentAccess;
    private $fetchDeploymentProjects;

    public function __construct(AuthoriseDeploymentAccess $authoriseDeploymentAccess, FetchDeploymentProjects $fetchDeploymentProjects)
    {
        $this->authoriseDeploymentAccess = $authoriseDeploymentAccess;
        $this->fetchDeploymentProjects = $fetchDeploymentProjects;
    }
    /**
     * @Route("/api/Deployments/Projects/GetDeploymentProjectsController/get", methods={"POST"}, name="Get Deployment Projects")
     */
    public function get(int $userId, int $deploymentId)
    {
        $this->authoriseDeploymentAccess->authorise($userId, $deploymentId);
        return $this->fetchDeploymentProjects->fetchAll($deploymentId);
    }
}
