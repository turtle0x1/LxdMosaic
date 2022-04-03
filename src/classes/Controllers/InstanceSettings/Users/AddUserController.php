<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\Users;

use dhope0000\LXDClient\Tools\User\AddUser;
use Symfony\Component\Routing\Annotation\Route;

class AddUserController
{
    private $addUser;

    public function __construct(AddUser $addUser)
    {
        $this->addUser = $addUser;
    }
    /**
     * @Route("/api/InstanceSettings/Users/AddUserController/add", methods={"POST"}, name="Add user to LXDMosaic")
     */
    public function add(int $userId, string $username, string $password)
    {
        $this->addUser->add($userId, $username, $password);
        return ["state"=>"success", "message"=>"Addded user"];
    }
}
