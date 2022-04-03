<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\Users;

use dhope0000\LXDClient\Tools\User\SearchUsers;
use Symfony\Component\Routing\Annotation\Route;

class SeachUsersController
{
    private $searchUsers;

    public function __construct(SearchUsers $searchUsers)
    {
        $this->searchUsers = $searchUsers;
    }
    /**
     * @Route("/api/InstanceSettings/Users/SeachUsersController/search", methods={"POST"}, name="Search all users on LXDMosaic")
     */
    public function search(int $userId, string $search)
    {
        return $this->searchUsers->search($userId, $search);
    }
}
