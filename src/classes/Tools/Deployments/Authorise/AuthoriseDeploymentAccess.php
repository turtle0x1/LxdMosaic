<?php

namespace dhope0000\LXDClient\Tools\Deployments\Authorise;

use dhope0000\LXDClient\Model\Deployments\Projects\FetchDeploymentProjectsUsers;

class AuthoriseDeploymentAccess
{
    private FetchDeploymentProjectsUsers $fetchDeploymentProjectsUsers;

    public function __construct(
        FetchDeploymentProjectsUsers $fetchDeploymentProjectsUsers
    ) {
        $this->fetchDeploymentProjectsUsers = $fetchDeploymentProjectsUsers;
    }

    public function authorise(int $userId, int $deploymentId) :bool
    {
        $allowedAccess = $this->fetchDeploymentProjectsUsers->userHasAccess(
            $userId,
            $deploymentId
        );

        if ($allowedAccess === false) {
            throw new \Exception("No access to deployment", 1);
        }

        return true;
    }
}
