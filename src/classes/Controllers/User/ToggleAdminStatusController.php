<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\User\ToggleAdminStatus;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Attribute\Route;

class ToggleAdminStatusController
{
    public function __construct(
        private readonly ToggleAdminStatus $toggleAdminStatus,
        private readonly ValidatePermissions $validatePermissions
    ) {
    }

    #[Route(path: '/api/User/ToggleAdminStatusController/toggle', name: 'api_user_toggleadminstatuscontroller_toggle', methods: ['POST'])]
    public function toggle(int $userId, int $targetUser, int $status)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->toggleAdminStatus->toggle($targetUser, $status);
        return [
            'state' => 'success',
            'message' => 'Changed status',
        ];
    }
}
