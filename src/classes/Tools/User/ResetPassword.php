<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Users\UpdatePasswordHash;

class ResetPassword
{
    public function __construct(ValidatePermissions $validatePermissions, UpdatePasswordHash $updatePasswordHash)
    {
        $this->validatePermissions = $validatePermissions;
        $this->updatePasswordHash = $updatePasswordHash;
    }

    public function reset(int $userId, int $targetUserId, string $newPassword)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $this->updatePasswordHash->update($targetUserId, $passwordHash);

        return true;
    }
}
