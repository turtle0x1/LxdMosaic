<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Users\UpdatePasswordHash;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class ResetPassword
{
    public function __construct(
        ValidatePermissions $validatePermissions,
        UpdatePasswordHash $updatePasswordHash,
        FetchUserDetails $fetchUserDetails
    ) {
        $this->validatePermissions = $validatePermissions;
        $this->updatePasswordHash = $updatePasswordHash;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function reset(int $userId, int $targetUserId, string $newPassword)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        
        if ($this->fetchUserDetails->isFromLdap($targetUserId)) {
            throw new \Exception("User from LDAP this would have no effect!", 1);
        }

        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $this->updatePasswordHash->update($targetUserId, $passwordHash);

        return true;
    }
}
