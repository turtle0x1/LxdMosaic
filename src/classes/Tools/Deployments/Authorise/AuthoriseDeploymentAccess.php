<?php

namespace dhope0000\LXDClient\Tools\Deployments\Authorise;

use dhope0000\LXDClient\Model\Deployments\Projects\FetchDeploymentProjectsUsers;

class AuthoriseDeploymentAccess
{
    public function __construct(
        private readonly FetchDeploymentProjectsUsers $fetchDeploymentProjectsUsers
    ) {
    }

    public function authorise(int $userId, int $deploymentId): bool
    {
        $allowedAccess = $this->fetchDeploymentProjectsUsers->userHasAccess($userId, $deploymentId);

        if ($allowedAccess === false) {
            throw new \Exception('No access to deployment', 1);
        }

        return true;
    }
}
