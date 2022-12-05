<?php

namespace dhope0000\LXDClient\Controllers\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\AllowedProjects\GetUserAllowedProjects;
use Symfony\Component\Routing\Annotation\Route;

class GetUserAllowedProjectsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private GetUserAllowedProjects $getUserAllowedProjects;

    public function __construct(GetUserAllowedProjects $getUserAllowedProjects)
    {
        $this->getUserAllowedProjects = $getUserAllowedProjects;
    }
    /**
     * @Route("", name="Get a users allowed projects")
     */
    public function get(int $userId, int $targetUser) :array
    {
        return $this->getUserAllowedProjects->get($userId, $targetUser);
    }
}
