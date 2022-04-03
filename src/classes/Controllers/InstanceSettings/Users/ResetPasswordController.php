<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\Users;

use dhope0000\LXDClient\Tools\User\ResetPassword;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController
{
    private $resetPassword;

    public function __construct(ResetPassword $resetPassword)
    {
        $this->resetPassword = $resetPassword;
    }
    /**
     * @Route("/api/InstanceSettings/Users/ResetPasswordController/reset", methods={"POST"}, name="Reset user password for LXDMosaic")
     */
    public function reset(int $userId, int $targetUser, string $newPassword)
    {
        $this->resetPassword->reset($userId, $targetUser, $newPassword);
        return ["state"=>"success", "message"=>"Updated password"];
    }
}
