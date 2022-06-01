<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetCloudConfigs;

class GetCloudsConfigController
{
    private $getCloudConfigs;
    
    public function __construct(GetCloudConfigs $getCloudConfigs)
    {
        $this->getCloudConfigs = $getCloudConfigs;
    }

    public function get(int $userId, int $deploymentId)
    {
        return $this->getCloudConfigs->getAll($userId, $deploymentId);
    }
}
