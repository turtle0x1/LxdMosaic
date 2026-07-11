<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\Users;

use dhope0000\LXDClient\Tools\User\ResetPassword;
use Symfony\Component\Routing\Attribute\Route;

class ResetPasswordController
{
    public function __construct(
        private readonly ResetPassword $resetPassword
    ) {
    }

    #[Route(path: '/api/InstanceSettings/Users/ResetPasswordController/reset', name: 'Reset user password', methods: ['POST'])]
    public function reset(int $userId, int $targetUser, string $newPassword)
    {
        $this->resetPassword->reset($userId, $targetUser, $newPassword);
        return [
            'state' => 'success',
            'message' => 'Updated password',
        ];
    }
}
