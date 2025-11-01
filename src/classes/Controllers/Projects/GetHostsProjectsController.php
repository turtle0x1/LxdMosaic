<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\CreateProject;
use dhope0000\LXDClient\Tools\Projects\GetHostsProjects;
use Symfony\Component\Routing\Annotation\Route;

class GetHostsProjectsController
{
    private $getHostsProjects;
    
    public function __construct(GetHostsProjects $getHostsProjects)
    {
        $this->getHostsProjects = $getHostsProjects;
    }

    /**
     * @Route("/api/Projects/GetHostsProjectsController/get", name="api_projects_gethostsprojectscontroller_get", methods={"POST"})
     */
    public function get(int $userId)
    {
        return $this->getHostsProjects->getAll($userId);
    }
}
