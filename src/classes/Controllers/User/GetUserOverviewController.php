<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\GetUserOverview;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class GetUserOverviewController
{
    private GetUserOverview $getUserOverview;
    private ValidatePermissions $validatePermissions;

    public function __construct(
        GetUserOverview $getUserOverview,
        ValidatePermissions $validatePermissions
    ) {
        $this->getUserOverview = $getUserOverview;
        $this->validatePermissions = $validatePermissions;
    }

    public function get(int $userId, int $targetUser) :array
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        return $this->getUserOverview->get($userId, $targetUser);
    }
}
