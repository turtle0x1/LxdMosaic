<?php

namespace dhope0000\LXDClient\Tools\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\AllowedProjects\DeleteUserAccess;
use dhope0000\LXDClient\Model\Users\Projects\DeleteUserProject;

class RevokeAccess
{
    private ValidatePermissions $validatePermissions;
    private FetchUserDetails $fetchUserDetails;
    private DeleteUserAccess $deleteUserAccess;
    private DeleteUserProject $deleteUserProject;

    public function __construct(
        ValidatePermissions $validatePermissions,
        FetchUserDetails $fetchUserDetails,
        DeleteUserAccess $deleteUserAccess,
        DeleteUserProject $deleteUserProject
    ) {
        $this->validatePermissions = $validatePermissions;
        $this->fetchUserDetails = $fetchUserDetails;
        $this->deleteUserAccess = $deleteUserAccess;
        $this->deleteUserProject = $deleteUserProject;
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
