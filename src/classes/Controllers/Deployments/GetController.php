<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetDeployments;
use Symfony\Component\Routing\Annotation\Route;

class GetController
{
    public function __construct(
        private readonly GetDeployments $getDeployments
    ) {
    }

    /**
     * @Route("/api/Deployments/GetController/getAll", name="api_deployments_getcontroller_getall", methods={"POST"})
     */
    public function getAll(int $userId)
    {
        return $this->getDeployments->getAll($userId);
    }
}
