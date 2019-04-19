<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\Deployments\InsertDeployment;
use dhope0000\LXDClient\Model\CloudConfig\GetConfig;
use dhope0000\LXDClient\Model\Deployments\CloudConfig\AddCloudConfig;

class CreateDeployment
{
    public function __construct(
        InsertDeployment $insertDeployment,
        GetConfig $getConfig,
        AddCloudConfig $addCloudConfig
    ) {
        $this->insertDeployment = $insertDeployment;
        $this->getConfig = $getConfig;
        $this->addCloudConfig = $addCloudConfig;
    }

    public function create(string $name, array $cloudConfigs)
    {
        $this->insertDeployment->insert($name);
        $deploymentId = $this->insertDeployment->getDeploymentId();

        foreach ($cloudConfigs as $cloudConfigId) {
            $latestRev = $this->getConfig->getLatestConfig($cloudConfigId);
            $this->addCloudConfig->add($deploymentId, $latestRev["revisionId"]);
        }

        return true;
    }
}
