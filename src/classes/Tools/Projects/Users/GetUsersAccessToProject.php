<?php

namespace dhope0000\LXDClient\Tools\Projects\Users;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Objects\Host;

class GetUsersAccessToProject
{
    private ValidatePermissions $validatePermissions;
    private FetchAllowedProjects $fetchAllowedProjects;

    public function __construct(ValidatePermissions $validatePermissions, FetchAllowedProjects $fetchAllowedProjects)
    {
        $this->validatePermissions = $validatePermissions;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
    }

    public function get(int $userId, Host $host, string $project) :array
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        return $this->fetchAllowedProjects->fetchForHostProject($host->getHostId(), $project);
    }
}
