<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchUsers;

class SearchUsers
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly FetchUsers $fetchUsers
    ) {
    }

    public function search(int $userId, string $search)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        return $this->fetchUsers->search($search);
    }
}
