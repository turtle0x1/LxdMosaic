<?php

namespace dhope0000\LXDClient\Tools\Deployments\Containers;

use dhope0000\LXDClient\Model\Deployments\Containers\InsertDeploymentContainer;

class StoreDeployedContainerNames
{
    public function __construct(
        private readonly InsertDeploymentContainer $insertDeploymentContainer
    ) {
    }

    public function store(int $deploymentId, array $deployedContainerInformation)
    {
        foreach ($deployedContainerInformation as $container) {
            $this->validateContainer($container);

            $this->insertDeploymentContainer->insert($deploymentId, $container['hostId'], $container['name']);
        }

        return true;
    }

    private function validateContainer(array $container)
    {
        if (!isset($container['hostId'])) {
            throw new \Exception('Missing hostId', 1);
        } elseif (!isset($container['name'])) {
            throw new \Exception('Missing name', 1);
        }

        return true;
    }
}
