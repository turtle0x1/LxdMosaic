<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetDeployment;

class GetDeploymentController
{
    private GetDeployment $getDeployment;

    public function __construct(GetDeployment $getDeployment)
    {
        $this->getDeployment = $getDeployment;
    }

    public function get(int $userId, int $deploymentId)
    {
        return $this->getDeployment->get($userId, $deploymentId);
    }
}
