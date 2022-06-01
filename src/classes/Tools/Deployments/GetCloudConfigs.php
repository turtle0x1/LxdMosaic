<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Tools\Deployments\Authorise\AuthoriseDeploymentAccess;
use dhope0000\LXDClient\Model\Deployments\CloudConfig\FetchCloudConfigs;

class GetCloudConfigs
{
    private $authoriseDeploymentAccess;
    private $fetchCloudConfigs;
    
    public function __construct(
        AuthoriseDeploymentAccess $authoriseDeploymentAccess,
        FetchCloudConfigs $fetchCloudConfigs
    ) {
        $this->authoriseDeploymentAccess = $authoriseDeploymentAccess;
        $this->fetchCloudConfigs = $fetchCloudConfigs;
    }

    public function getAll(int $userId, int $deploymentId)
    {
        $this->authoriseDeploymentAccess->authorise($userId, $deploymentId);
        return $this->fetchCloudConfigs->getAll($deploymentId);
    }
}
