<?php

namespace dhope0000\LXDClient\Tools\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\AllowedProjects\DeleteUserAccess;

class RevokeAccess
{
    public function __construct(
        ValidatePermissions $validatePermissions,
        FetchUserDetails $fetchUserDetails,
        DeleteUserAccess $deleteUserAccess
    ) {
        $this->validatePermissions = $validatePermissions;
        $this->fetchUserDetails = $fetchUserDetails;
        $this->deleteUserAccess = $deleteUserAccess;
    }

    public function revoke(int $userId, int $targetUserId, int $hostId, string $project)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $isAdmin = (bool) $this->fetchUserDetails->isAdmin($targetUserId);

        if ($isAdmin) {
            throw new \Exception("The user is admin - this doesn't make sense", 1);
        }

        $this->deleteUserAccess->delete($targetUserId, $hostId, $project);
    }
}
