<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetDeployment;

class GetDeploymentController
{
    public function __construct(GetDeployment $getDeployment)
    {
        $this->getDeployment = $getDeployment;
    }

    public function get(int $deploymentId)
    {
        return $this->getDeployment->get($deploymentId);
    }
}
