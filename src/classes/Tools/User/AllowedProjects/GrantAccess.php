<?php

namespace dhope0000\LXDClient\Tools\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\AllowedProjects\InsertUserAccess;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;

class GrantAccess
{
    private ValidatePermissions $validatePermissions;
    private FetchUserDetails $fetchUserDetails;
    private InsertUserAccess $insertUserAccess;
    private FetchAllowedProjects $fetchAllowedProjects;

    public function __construct(
        ValidatePermissions $validatePermissions,
        FetchUserDetails $fetchUserDetails,
        InsertUserAccess $insertUserAccess,
        FetchAllowedProjects $fetchAllowedProjects
    ) {
        $this->validatePermissions = $validatePermissions;
        $this->fetchUserDetails = $fetchUserDetails;
        $this->insertUserAccess = $insertUserAccess;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
    }

    public function grant(int $userId, int $targetUserId, array $hosts, array $projects) :bool
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
