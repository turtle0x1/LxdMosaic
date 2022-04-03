<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetCloudConfigs;
use Symfony\Component\Routing\Annotation\Route;

class GetCloudsConfigController
{
    public function __construct(GetCloudConfigs $getCloudConfigs)
    {
        $this->getCloudConfigs = $getCloudConfigs;
    }
    /**
     * @Route("/api/Deployments/GetCloudsConfigController/get", methods={"POST"}, name="Get cloud configs in a deployment")
     */
    public function get(int $userId, int $deploymentId)
    {
        return $this->getCloudConfigs->getAll($userId, $deploymentId);
    }
}
