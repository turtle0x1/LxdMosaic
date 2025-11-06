<?php

namespace dhope0000\LXDClient\Tools\User\AllowedProjects;

use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Users\AllowedProjects\InsertUserAccess;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class GrantAccess
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly InsertUserAccess $insertUserAccess,
        private readonly FetchAllowedProjects $fetchAllowedProjects
    ) {
    }

    public function grant(int $userId, int $targetUserId, array $hosts, array $projects)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $isAdmin = (bool) $this->fetchUserDetails->isAdmin($targetUserId);

        if ($isAdmin) {
            throw new \Exception("The user is admin - this doesn't make sense", 1);
        }

        $existingAccess = $this->fetchAllowedProjects->fetchAll($targetUserId);

        foreach ($hosts as $hostId) {
            foreach ($projects as $project) {
                if (isset($existingAccess[$hostId]) && in_array($project, $existingAccess[$hostId])) {
                    continue;
                }
                $this->insertUserAccess->insert($userId, $targetUserId, $hostId, $project);
            }
        }
        return true;
    }
}
