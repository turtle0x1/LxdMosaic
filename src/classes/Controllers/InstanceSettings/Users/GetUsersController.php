<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\Users;

use dhope0000\LXDClient\Tools\User\GetUsers;

class GetUsersController
{
    private GetUsers $getUsers;

    public function __construct(GetUsers $getUsers)
    {
        $this->getUsers = $getUsers;
    }

    public function getAll(int $userId)
    {
        return $this->getUsers->getAll($userId);
    }
}
