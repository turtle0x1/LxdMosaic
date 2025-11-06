<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\UpdateLoginStatus;

class ToggleLoginStatus
{
    public function __construct(
        private readonly UpdateLoginStatus $updateLoginStatus,
        private readonly FetchUserDetails $fetchUserDetails
    ) {
    }

    public function toggle(int $targetUser, int $status)
    {
        $isLoginDisabled = $this->fetchUserDetails->isLoginDisabled($targetUser);

        $status = (int) $status;

        if ($status !== 1 && $status !== 0) {
            throw new \Exception('Status should be 1 for disabled 0 for enabled', 1);
        }

        if ($status == 0 && !$isLoginDisabled) {
            throw new \Exception('Trying enable logging in for a user that can already login.', 1);
        } elseif ($status == 1 && $isLoginDisabled) {
            throw new \Exception('Trying to disable logging in for a user that already cant login.', 1);
        }

        $this->updateLoginStatus->update($targetUser, $status);
    }
}
