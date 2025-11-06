<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\Deployments\CloudConfig\FetchCloudConfigs;
use dhope0000\LXDClient\Tools\Deployments\Authorise\AuthoriseDeploymentAccess;

class GetCloudConfigs
{
    public function __construct(
        private readonly AuthoriseDeploymentAccess $authoriseDeploymentAccess,
        private readonly FetchCloudConfigs $fetchCloudConfigs
    ) {
    }

    public function getAll(int $userId, int $deploymentId)
    {
        $this->authoriseDeploymentAccess->authorise($userId, $deploymentId);
        return $this->fetchCloudConfigs->getAll($deploymentId);
    }
}
