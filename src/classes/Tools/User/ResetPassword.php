<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\UpdatePasswordHash;

class ResetPassword
{
    public function __construct(UpdatePasswordHash $updatePasswordHash)
    {
        $this->updatePasswordHash = $updatePasswordHash;
    }

    public function reset(int $userId, string $newPassword)
    {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $this->updatePasswordHash->update($userId, $passwordHash);

        return true;
    }
}
