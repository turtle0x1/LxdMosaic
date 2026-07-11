<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\Users;

use dhope0000\LXDClient\Tools\User\SearchUsers;
use Symfony\Component\Routing\Attribute\Route;

class SeachUsersController
{
    public function __construct(
        private readonly SearchUsers $searchUsers
    ) {
    }

    #[Route(path: '/api/InstanceSettings/Users/SeachUsersController/search', name: 'Search users', methods: ['POST'])]
    public function search(int $userId, string $search)
    {
        return $this->searchUsers->search($userId, $search);
    }
}
