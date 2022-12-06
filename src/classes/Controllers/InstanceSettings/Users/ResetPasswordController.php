<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\Users;

use dhope0000\LXDClient\Tools\User\ResetPassword;

class ResetPasswordController
{
    private ResetPassword $resetPassword;

    public function __construct(ResetPassword $resetPassword)
    {
        $this->resetPassword = $resetPassword;
    }

    public function reset(int $userId, int $targetUser, string $newPassword)
    {
        $this->resetPassword->reset($userId, $targetUser, $newPassword);
        return ["state"=>"success", "message"=>"Updated password"];
    }
}
