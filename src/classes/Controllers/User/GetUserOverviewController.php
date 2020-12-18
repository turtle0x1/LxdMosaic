<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\GetUserOverview;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class GetUserOverviewController
{
    public function __construct(
        GetUserOverview $getUserOverview,
        ValidatePermissions $validatePermissions
    ) {
        $this->getUserOverview = $getUserOverview;
        $this->validatePermissions = $validatePermissions;
    }

    public function get(int $userId, int $targetUser)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        return $this->getUserOverview->get($userId, $targetUser);
    }
}
