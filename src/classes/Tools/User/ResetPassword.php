<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\UpdatePasswordHash;
use dhope0000\LXDClient\Tools\User\Password\CheckPasswordPolicy;

class ResetPassword
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly UpdatePasswordHash $updatePasswordHash,
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly CheckPasswordPolicy $checkPasswordPolicy
    ) {
    }

    public function reset(int $userId, int $targetUserId, string $newPassword)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        if ($this->fetchUserDetails->isFromLdap($targetUserId)) {
            throw new \Exception('User from LDAP this would have no effect!', 1);
        }

        $this->checkPasswordPolicy->conforms($newPassword);

        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $this->updatePasswordHash->update($targetUserId, $passwordHash);

        return true;
    }
}
