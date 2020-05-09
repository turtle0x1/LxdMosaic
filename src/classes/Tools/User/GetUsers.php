<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Users\FetchUsers;

class GetUsers
{
    public function __construct(ValidatePermissions $validatePermissions, FetchUsers $fetchUsers)
    {
        $this->validatePermissions = $validatePermissions;
        $this->fetchUsers = $fetchUsers;
    }

    public function getAll($userId)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        return $this->fetchUsers->fetchAll();
    }
}
