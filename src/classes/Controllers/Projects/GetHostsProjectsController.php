<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\CreateProject;
use dhope0000\LXDClient\Tools\Projects\GetHostsProjects;
use Symfony\Component\Routing\Annotation\Route;

class GetHostsProjectsController
{
    public function __construct(GetHostsProjects $getHostsProjects)
    {
        $this->getHostsProjects = $getHostsProjects;
    }
    /**
     * @Route("/api/Projects/GetHostsProjectsController/get", methods={"POST"}, name="Get all projects on all hosts", options={"rbac" = "projects.read"})
     */
    public function get(int $userId)
    {
        return $this->getHostsProjects->getAll($userId);
    }
}
