<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;

class ValidatePermissions
{
    public function __construct(
        FetchUserDetails $fetchUserDetails,
        FetchAllowedProjects $fetchAllowedProjects
    ) {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
    }

    public function isAdmin(int $userId) :bool
    {
        return (bool) $this->fetchUserDetails->isAdmin($userId);
    }

    public function canAccessHostProjectOrThrow(int $userId, int $hostId, string $project)
    {
        $isAdmin = $this->isAdmin($userId);
        if ($isAdmin) {
            return true;
        }

        $allowedProjects = $this->fetchAllowedProjects->fetchForHost($userId, $hostId);

        if (empty($allowedProjects)) {
            throw new \Exception("Not access to host", 1);
        }

        if (!in_array($project, $allowedProjects)) {
            throw new \Exception("No acess to project", 1);
        }

        return true;
    }

    public function canAccessHostProject(int $userId, int $hostId, string $project) :bool
    {
        $isAdmin = $this->isAdmin($userId);
        if ($isAdmin) {
            return true;
        }

        $allowedProjects = $this->fetchAllowedProjects->fetchForHost($userId, $hostId);

        if (empty($allowedProjects)) {
            return false;
        }

        if (!in_array($project, $allowedProjects)) {
            return false;
        }

        return true;
    }

    public function isAdminOrThrow(int $userId) :bool
    {
        if (!$this->isAdmin($userId)) {
            throw new \Exception("Not Admin", 1);
        }
        return true;
    }
}
