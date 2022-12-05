<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Users\FetchUsers;

class SearchUsers
{
    private ValidatePermissions $validatePermissions;
    private FetchUsers $fetchUsers;

    public function __construct(ValidatePermissions $validatePermissions, FetchUsers $fetchUsers)
    {
        $this->validatePermissions = $validatePermissions;
        $this->fetchUsers = $fetchUsers;
    }

    public function search(int $userId, string $search) :array
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        return $this->fetchUsers->search($search);
    }
}
