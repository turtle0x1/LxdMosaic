<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\User\ToggleLoginStatus;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class ToggleLoginStatusController
{
    private ToggleLoginStatus $toggleLoginStatus;
    private ValidatePermissions $validatePermissions;

    public function __construct(
        ToggleLoginStatus $toggleLoginStatus,
        ValidatePermissions $validatePermissions
    ) {
        $this->toggleLoginStatus = $toggleLoginStatus;
        $this->validatePermissions = $validatePermissions;
    }

    public function toggle(int $userId, int $targetUser, int $status) :array
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->toggleLoginStatus->toggle($targetUser, $status);
        return ["state"=>"success", "message"=>"Changed status"];
    }
}
