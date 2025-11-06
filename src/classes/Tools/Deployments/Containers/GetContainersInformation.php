<?php

namespace dhope0000\LXDClient\Tools\Deployments\Containers;

use dhope0000\LXDClient\Model\Deployments\Containers\GetDeployedContainers;

class GetContainersInformation
{
    public function __construct(
        private readonly GetDeployedContainers $getDeployedContainers
    ) {
    }

    public function getContainersInDeployment(int $deploymentId)
    {
        return $this->getDeployedContainers->getByDeploymentId($deploymentId);
    }
}
