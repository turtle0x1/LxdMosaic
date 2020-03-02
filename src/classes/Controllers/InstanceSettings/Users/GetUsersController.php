<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\Users;

use dhope0000\LXDClient\Model\Users\FetchUsers;

class GetUsersController
{
    private $fetchUsers;

    public function __construct(FetchUsers $fetchUsers)
    {
        $this->fetchUsers = $fetchUsers;
    }

    public function getAll()
    {
        return $this->fetchUsers->fetchAll();
    }
}
