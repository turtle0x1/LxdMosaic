<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Tools\User\UserSession;
use dhope0000\LXDClient\Model\Users\FetchUsers;

class GetUsers
{
    public function __construct(UserSession $userSession, FetchUsers $fetchUsers)
    {
        $this->userSession = $userSession;
        $this->fetchUsers = $fetchUsers;
    }

    public function getAll()
    {
        $this->userSession->isAdminOrThrow();

        return $this->fetchUsers->fetchAll();
    }
}
