<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\Users;

use dhope0000\LXDClient\Tools\User\GetUsers;

class GetUsersController
{
    private $getUsers;

    public function __construct(GetUsers $getUsers)
    {
        $this->getUsers = $getUsers;
    }

    public function getAll()
    {
        return $this->getUsers->getAll();
    }
}
