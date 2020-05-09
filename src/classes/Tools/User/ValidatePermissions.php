<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class ValidatePermissions
{
    public function __construct(FetchUserDetails $fetchUserDetails)
    {
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function isAdmin(int $userId) :bool
    {
        return (bool) $this->fetchUserDetails->isAdmin($userId);
    }

    public function isAdminOrThrow(int $userId) :bool
    {
        if (!$this->isAdmin($userId)) {
            throw new \Exception("Not Admin", 1);
        }
        return true;
    }
}
