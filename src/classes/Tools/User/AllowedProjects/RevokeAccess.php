<?php

namespace dhope0000\LXDClient\Tools\User\AllowedProjects;

use dhope0000\LXDClient\Model\Users\AllowedProjects\DeleteUserAccess;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\Projects\DeleteUserProject;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class RevokeAccess
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly DeleteUserAccess $deleteUserAccess,
        private readonly DeleteUserProject $deleteUserProject
    ) {
    }

    public function revoke(int $userId, int $targetUserId, int $hostId, string $project)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $isAdmin = (bool) $this->fetchUserDetails->isAdmin($targetUserId);

        if ($isAdmin) {
            throw new \Exception("The user is admin - this doesn't make sense", 1);
        }

        $this->deleteUserAccess->delete($targetUserId, $hostId, $project);

        $this->deleteUserProject->removeFromProject($targetUserId, $hostId, $project);
    }
}
