<?php

namespace dhope0000\LXDClient\Tools\Deployments\Containers;

use dhope0000\LXDClient\Model\Deployments\Containers\GetDeployedContainers;

class GetContainersInformation
{
    private $getDeployedContainers;
    
    public function __construct(GetDeployedContainers $getDeployedContainers)
    {
        $this->getDeployedContainers = $getDeployedContainers;
    }

    public function getContainersInDeployment(int $deploymentId)
    {
        return $this->getDeployedContainers->getByDeploymentId($deploymentId);
    }
}
