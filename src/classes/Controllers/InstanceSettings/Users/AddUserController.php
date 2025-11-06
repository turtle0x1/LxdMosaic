<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\Users;

use dhope0000\LXDClient\Tools\User\AddUser;
use Symfony\Component\Routing\Annotation\Route;

class AddUserController
{
    public function __construct(
        private readonly AddUser $addUser
    ) {
    }

    /**
     * @Route("/api/InstanceSettings/Users/AddUserController/add", name="api_instancesettings_users_addusercontroller_add", methods={"POST"})
     */
    public function add(int $userId, string $username, string $password)
    {
        $this->addUser->add($userId, $username, $password);
        return [
            'state' => 'success',
            'message' => 'Addded user',
        ];
    }
}
