<?php

namespace dhope0000\LXDClient\Controllers\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\AllowedProjects\GetUserAllowedProjects;
use Symfony\Component\Routing\Annotation\Route;

class GetUserAllowedProjectsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly GetUserAllowedProjects $getUserAllowedProjects
    ) {
    }

    /**
     * @Route("/api/User/AllowedProjects/GetUserAllowedProjectsController/get", name="Get a users allowed projects", methods={"POST"})
     */
    public function get(int $userId, int $targetUser)
    {
        return $this->getUserAllowedProjects->get($userId, $targetUser);
    }
}
