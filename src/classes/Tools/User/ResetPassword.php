<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Users\UpdatePasswordHash;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\User\Password\CheckPasswordPolicy;

class ResetPassword
{
    public function __construct(
        ValidatePermissions $validatePermissions,
        UpdatePasswordHash $updatePasswordHash,
        FetchUserDetails $fetchUserDetails,
        CheckPasswordPolicy  $checkPasswordPolicy
    ) {
        $this->validatePermissions = $validatePermissions;
        $this->updatePasswordHash = $updatePasswordHash;
        $this->fetchUserDetails = $fetchUserDetails;
        $this->checkPasswordPolicy = $checkPasswordPolicy;
    }

    public function reset(int $userId, int $targetUserId, string $newPassword)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        if ($this->fetchUserDetails->isFromLdap($targetUserId)) {
            throw new \Exception("User from LDAP this would have no effect!", 1);
        }

        $this->checkPasswordPolicy->conforms($newPassword);

        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $this->updatePasswordHash->update($targetUserId, $passwordHash);

        return true;
    }
}
