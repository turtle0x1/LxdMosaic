<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\GetUserOverview;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Annotation\Route;

class GetUserOverviewController
{
    public function __construct(
        private readonly GetUserOverview $getUserOverview,
        private readonly ValidatePermissions $validatePermissions
    ) {
    }

    /**
     * @Route("/api/User/GetUserOverviewController/get", name="api_user_getuseroverviewcontroller_get", methods={"POST"})
     */
    public function get(int $userId, int $targetUser)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        return $this->getUserOverview->get($userId, $targetUser);
    }
}
