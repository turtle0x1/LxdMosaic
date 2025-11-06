<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\CloudConfig\GetConfig;
use dhope0000\LXDClient\Model\Deployments\CloudConfig\AddCloudConfig;
use dhope0000\LXDClient\Model\Deployments\InsertDeployment;

class CreateDeployment
{
    public function __construct(
        private readonly InsertDeployment $insertDeployment,
        private readonly GetConfig $getConfig,
        private readonly AddCloudConfig $addCloudConfig
    ) {
    }

    public function create(string $name, array $cloudConfigs)
    {
        $this->insertDeployment->insert($name);
        $deploymentId = $this->insertDeployment->getDeploymentId();

        foreach ($cloudConfigs as $cloudConfigId) {
            $latestRev = $this->getConfig->getLatestConfig($cloudConfigId);
            $this->addCloudConfig->add($deploymentId, $latestRev['revisionId']);
        }

        return $deploymentId;
    }
}
