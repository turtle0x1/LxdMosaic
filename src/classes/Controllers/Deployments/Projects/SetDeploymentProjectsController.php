<?php

namespace dhope0000\LXDClient\Controllers\Deployments\Projects;

use dhope0000\LXDClient\Tools\Deployments\Projects\SetDeploymentProjects;
use Symfony\Component\Routing\Annotation\Route;
use \DI\Container;

class SetDeploymentProjectsController
{
    private SetDeploymentProjects $setDeploymentProjects;
    private Container $container;

    public function __construct(SetDeploymentProjects $setDeploymentProjects, Container $container)
    {
        $this->setDeploymentProjects = $setDeploymentProjects;
        $this->container = $container;
    }
    /**
     * @Route("", name="Set Deployment Projects")
     */
    public function set(int $userId, int $deploymentId, array $newProjectsLayout) :array
    {
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", "beginTransaction"]);
        $this->setDeploymentProjects->set($userId, $deploymentId, $newProjectsLayout);
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", "commitTransaction"]);
        return ["state"=>"success", "message"=>"Updated Projects"];
    }
}
