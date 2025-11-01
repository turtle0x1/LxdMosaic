<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetDeployment;
use Symfony\Component\Routing\Annotation\Route;

class GetDeploymentController
{
    private $getDeployment;

    public function __construct(GetDeployment $getDeployment)
    {
        $this->getDeployment = $getDeployment;
    }

    /**
     * @Route("/api/Deployments/GetDeploymentController/get", name="api_deployments_getdeploymentcontroller_get", methods={"POST"})
     */
    public function get(int $userId, int $deploymentId)
    {
        return $this->getDeployment->get($userId, $deploymentId);
    }
}
