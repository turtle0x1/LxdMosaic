<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetCloudConfigs;

class GetCloudsConfigController
{
    public function __construct(GetCloudConfigs $getCloudConfigs)
    {
        $this->getCloudConfigs = $getCloudConfigs;
    }

    public function get(int $deploymentId)
    {
        return $this->getCloudConfigs->getAll($deploymentId);
    }
}
