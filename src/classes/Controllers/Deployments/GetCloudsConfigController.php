<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetCloudConfigs;
use Symfony\Component\Routing\Annotation\Route;

class GetCloudsConfigController
{
    private $getCloudConfigs;
    
    public function __construct(GetCloudConfigs $getCloudConfigs)
    {
        $this->getCloudConfigs = $getCloudConfigs;
    }

    /**
     * @Route("/api/Deployments/GetCloudsConfigController/get", name="api_deployments_getcloudsconfigcontroller_get", methods={"POST"})
     */
    public function get(int $userId, int $deploymentId)
    {
        return $this->getCloudConfigs->getAll($userId, $deploymentId);
    }
}
