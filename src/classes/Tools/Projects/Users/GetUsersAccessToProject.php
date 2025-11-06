<?php

namespace dhope0000\LXDClient\Tools\Projects\Users;

use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class GetUsersAccessToProject
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly FetchAllowedProjects $fetchAllowedProjects
    ) {
    }

    public function get(int $userId, Host $host, string $project): array
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        return $this->fetchAllowedProjects->fetchForHostProject($host->getHostId(), $project);
    }
}
