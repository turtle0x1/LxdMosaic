<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\User\ToggleLoginStatus;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Annotation\Route;

class ToggleLoginStatusController
{
    private $toggleLoginStatus;
    private $validatePermissions;
    
    public function __construct(
        ToggleLoginStatus $toggleLoginStatus,
        ValidatePermissions $validatePermissions
    ) {
        $this->toggleLoginStatus = $toggleLoginStatus;
        $this->validatePermissions = $validatePermissions;
    }

    /**
     * @Route("/api/User/ToggleLoginStatusController/toggle", name="api_user_toggleloginstatuscontroller_toggle", methods={"POST"})
     */
    public function toggle(int $userId, int $targetUser, int $status)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->toggleLoginStatus->toggle($targetUser, $status);
        return ["state"=>"success", "message"=>"Changed status"];
    }
}
