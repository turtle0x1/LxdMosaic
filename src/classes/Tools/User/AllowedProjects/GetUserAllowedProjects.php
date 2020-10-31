<?php

namespace dhope0000\LXDClient\Tools\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class GetUserAllowedProjects
{
    public function __construct(
        ValidatePermissions $validatePermissions,
        FetchAllowedProjects $fetchAllowedProjects,
        GetDetails $getDetails,
        FetchUserDetails $fetchUserDetails
    ) {
        $this->validatePermissions = $validatePermissions;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->getDetails = $getDetails;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function get(int $userId, int $targetUserId)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $isAdmin = (bool) $this->fetchUserDetails->isAdmin($targetUserId);
        $output = [];

        if (!$isAdmin) {
            $allowedProjects = $this->fetchAllowedProjects->fetchAll($targetUserId);
            foreach ($allowedProjects as $hostId => $projects) {
                $host = $this->getDetails->fetchHost($hostId);
                $host->setCustomProp("projects", $projects);

                $output[] = $host;
            }
        }

        return [
            "isAdmin"=>$isAdmin,
            "projects"=>$output
        ];
    }
}
