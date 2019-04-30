<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\Deployments\CloudConfig\FetchCloudConfigs;

class GetCloudConfigs
{
    public function __construct(FetchCloudConfigs $fetchCloudConfigs)
    {
        $this->fetchCloudConfigs = $fetchCloudConfigs;
    }

    public function getAll(int $deploymentId)
    {
        return $this->fetchCloudConfigs->getAll($deploymentId);
    }
}
