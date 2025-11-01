<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\Users;

use dhope0000\LXDClient\Tools\User\GetUsers;
use Symfony\Component\Routing\Annotation\Route;

class GetUsersController
{
    private $getUsers;

    public function __construct(GetUsers $getUsers)
    {
        $this->getUsers = $getUsers;
    }

    /**
     * @Route("/api/InstanceSettings/Users/GetUsersController/getAll", name="api_instancesettings_users_getuserscontroller_getall", methods={"POST"})
     */
    public function getAll(int $userId)
    {
        return $this->getUsers->getAll($userId);
    }
}
