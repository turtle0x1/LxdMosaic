<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\Deployments\CloudConfig\GetCloudConfigs;

class GetDeployment
{
    public function __construct(GetCloudConfigs $getCloudConfigs)
    {
        $this->getCloudConfigs = $getCloudConfigs;
    }

    public function get(int $deploymentId)
    {
        $output = [];
        $output["cloudConfigs"] = $this->getCloudConfigs->getAll($deploymentId);
        return $output;
    }
}
