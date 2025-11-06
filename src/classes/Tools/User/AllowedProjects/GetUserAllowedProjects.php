<?php

namespace dhope0000\LXDClient\Tools\User\AllowedProjects;

use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class GetUserAllowedProjects
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly FetchAllowedProjects $fetchAllowedProjects,
        private readonly GetDetails $getDetails,
        private readonly FetchUserDetails $fetchUserDetails
    ) {
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
                $host->setCustomProp('projects', $projects);

                $output[] = $host;
            }
        }

        return [
            'isAdmin' => $isAdmin,
            'projects' => $output,
        ];
    }
}
