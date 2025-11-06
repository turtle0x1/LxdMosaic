<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\GetHostsProjects;
use Symfony\Component\Routing\Attribute\Route;

class GetHostsProjectsController
{
    public function __construct(
        private readonly GetHostsProjects $getHostsProjects
    ) {
    }

    #[Route(path: '/api/Projects/GetHostsProjectsController/get', name: 'api_projects_gethostsprojectscontroller_get', methods: ['POST'])]
    public function get(int $userId)
    {
        return $this->getHostsProjects->getAll($userId);
    }
}
