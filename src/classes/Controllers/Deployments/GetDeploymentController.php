<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetDeployment;
use Symfony\Component\Routing\Annotation\Route;

class GetDeploymentController
{
    public function __construct(GetDeployment $getDeployment)
    {
        $this->getDeployment = $getDeployment;
    }
    /**
     * @Route("/api/Deployments/GetDeploymentController/get", methods={"POST"}, name="Get deployment details")
     */
    public function get(int $userId, int $deploymentId)
    {
        return $this->getDeployment->get($userId, $deploymentId);
    }
}
