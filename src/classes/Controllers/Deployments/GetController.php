<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetDeployments;
use Symfony\Component\Routing\Annotation\Route;

class GetController
{
    public function __construct(GetDeployments $getDeployments)
    {
        $this->getDeployments = $getDeployments;
    }
    /**
     * @Route("/api/Deployments/GetController/getAll", methods={"POST"}, name="Get all deployments", options={"rbac" = "deployments.read"})
     */
    public function getAll(int $userId)
    {
        return $this->getDeployments->getAll($userId);
    }
}
