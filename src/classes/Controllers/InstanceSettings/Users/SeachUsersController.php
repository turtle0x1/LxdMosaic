<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\Users;

use dhope0000\LXDClient\Tools\User\SearchUsers;

class SeachUsersController
{
    private SearchUsers $searchUsers;

    public function __construct(SearchUsers $searchUsers)
    {
        $this->searchUsers = $searchUsers;
    }

    public function search(int $userId, string $search)
    {
        return $this->searchUsers->search($userId, $search);
    }
}
