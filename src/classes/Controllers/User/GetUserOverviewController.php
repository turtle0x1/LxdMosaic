<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\GetUserOverview;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Annotation\Route;

class GetUserOverviewController
{
    private $getUserOverview;
    private $validatePermissions;
    
    public function __construct(
        GetUserOverview $getUserOverview,
        ValidatePermissions $validatePermissions
    ) {
        $this->getUserOverview = $getUserOverview;
        $this->validatePermissions = $validatePermissions;
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
