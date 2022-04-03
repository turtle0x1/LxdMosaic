<?php

namespace dhope0000\LXDClient\Controllers\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\AllowedProjects\GetUserAllowedProjects;
use Symfony\Component\Routing\Annotation\Route;

class GetUserAllowedProjectsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $getUserAllowedProjects;

    public function __construct(GetUserAllowedProjects $getUserAllowedProjects)
    {
        $this->getUserAllowedProjects = $getUserAllowedProjects;
    }
    /**
     * @Route("/api/User/AllowedProjects/GetUserAllowedProjectsController/get", methods={"POST"}, name="Get a users allowed projects")
     */
    public function get(int $userId, int $targetUser)
    {
        return $this->getUserAllowedProjects->get($userId, $targetUser);
    }
}
