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
     * @Route("/api/InstanceSettings/Users/GetUsersController/getAll", methods={"POST"}, name="Get LXDMosaic users")
     */
    public function getAll(int $userId)
    {
        return $this->getUsers->getAll($userId);
    }
}
