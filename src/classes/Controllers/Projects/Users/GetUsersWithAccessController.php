<?php

namespace dhope0000\LXDClient\Controllers\Projects\Users;

use dhope0000\LXDClient\Tools\Projects\Users\GetUsersAccessToProject;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetUsersWithAccessController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(GetUsersAccessToProject $getUsersAccessToProject)
    {
        $this->getUsersAccessToProject = $getUsersAccessToProject;
    }
    /**
     * @Route("/api/Projects/Users/GetUsersWithAccessController/get", methods={"POST"}, name="Get users with access to a host project", options={"rbac" = "lxdmosaic.user.access.read"})
     */
    public function get(
        int $userId,
        Host $host,
        string $project
    ) {
        return $this->getUsersAccessToProject->get($userId, $host, $project);
    }
}
