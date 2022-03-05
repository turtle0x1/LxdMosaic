<?php

namespace dhope0000\LXDClient\Controllers\Deployments\Projects;

use dhope0000\LXDClient\Model\Deployments\Projects\FetchDeploymentProjects;
use Symfony\Component\Routing\Annotation\Route;

class GetDeploymentProjectsController
{
    private $fetchDeploymentProjects;

    public function __construct(FetchDeploymentProjects $fetchDeploymentProjects)
    {
        $this->fetchDeploymentProjects = $fetchDeploymentProjects;
    }
    /**
     * @Route("", name="Get Deployment Projects")
     */
    public function get(int $deploymentId)
    {
        return $this->fetchDeploymentProjects->fetchAll($deploymentId);
    }
}
