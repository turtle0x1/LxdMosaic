<?php

namespace dhope0000\LXDClient\Controllers\Projects\Users;

use dhope0000\LXDClient\Tools\Projects\Users\GetUsersAccessToProject;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetUsersWithAccessController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $getUsersAccessToProject;
    
    public function __construct(GetUsersAccessToProject $getUsersAccessToProject)
    {
        $this->getUsersAccessToProject = $getUsersAccessToProject;
    }
    /**
     * @Route("/api/Projects/Users/GetUsersWithAccessController/get", name="Get users with access to a host project", methods={"POST"})
     */
    public function get(
        int $userId,
        Host $host,
        string $project
    ) {
        return $this->getUsersAccessToProject->get($userId, $host, $project);
    }
}
