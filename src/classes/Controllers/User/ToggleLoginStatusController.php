<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\User\ToggleLoginStatus;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Attribute\Route;

class ToggleLoginStatusController
{
    public function __construct(
        private readonly ToggleLoginStatus $toggleLoginStatus,
        private readonly ValidatePermissions $validatePermissions
    ) {
    }

    #[Route(path: '/api/User/ToggleLoginStatusController/toggle', name: 'api_user_toggleloginstatuscontroller_toggle', methods: ['POST'])]
    public function toggle(int $userId, int $targetUser, int $status)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->toggleLoginStatus->toggle($targetUser, $status);
        return [
            'state' => 'success',
            'message' => 'Changed status',
        ];
    }
}
