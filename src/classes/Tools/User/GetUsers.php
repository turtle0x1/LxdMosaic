<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchUsers;

class GetUsers
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly FetchUsers $fetchUsers
    ) {
    }

    public function getAll($userId)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        return $this->fetchUsers->fetchAll();
    }
}
