<?php

namespace dhope0000\LXDClient\Tools\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\AllowedProjects\InsertUserAccess;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Objects\Host;

class GrantAccessToProject
{
    private $validatePermissions;
    private $fetchUserDetails;
    private $insertUserAccess;
    private $fetchAllowedProjects;
    
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
