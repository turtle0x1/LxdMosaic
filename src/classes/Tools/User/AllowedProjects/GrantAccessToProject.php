<?php

namespace dhope0000\LXDClient\Tools\User\AllowedProjects;

use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Users\AllowedProjects\InsertUserAccess;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class GrantAccessToProject
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly InsertUserAccess $insertUserAccess,
        private readonly FetchAllowedProjects $fetchAllowedProjects
    ) {
    }

    public function grant(int $userId, array $targetUsers, Host $host, string $project)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        $hostId = $host->getHostId();

        foreach ($targetUsers as $targetUserId) {
            $isAdmin = (bool) $this->fetchUserDetails->isAdmin($targetUserId);

            if ($isAdmin) {
                continue;
            }

            $existingAccess = $this->fetchAllowedProjects->fetchAll($targetUserId);

            if (isset($existingAccess[$hostId]) && in_array($project, $existingAccess[$hostId])) {
                continue;
            }

            $this->insertUserAccess->insert($userId, $targetUserId, $hostId, $project);
        }

        return true;
    }
}
