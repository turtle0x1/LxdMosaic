<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\UpdateAdminStatus;

class ToggleAdminStatus
{
    public function __construct(
        private readonly UpdateAdminStatus $updateAdminStatus,
        private readonly FetchUserDetails $fetchUserDetails
    ) {
    }

    public function toggle(int $targetUser, int $status)
    {
        $isAlreadyAdmin = $this->fetchUserDetails->isAdmin($targetUser);

        if ($status !== 1 && $status !== 0) {
            throw new \Exception('Status should be 1 for admin or 0 for removing admin', 1);
        }

        if ($status == 1 && $isAlreadyAdmin) {
            throw new \Exception('Trying to make an admin user an admin user.', 1);
        } elseif ($status == 0 && !$isAlreadyAdmin) {
            throw new \Exception('Trying to make an non-admin user a non-admin user.', 1);
        }

        $this->updateAdminStatus->update($targetUser, $status);
    }
}
