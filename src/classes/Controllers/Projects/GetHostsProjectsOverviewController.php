<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\GetHostProjectsOverview;
use Symfony\Component\Routing\Attribute\Route;

class GetHostsProjectsOverviewController
{
    public function __construct(
        private readonly GetHostProjectsOverview $getHostProjectsOverview
    ) {
    }

    #[Route(path: '/api/Projects/GetHostsProjectsOverviewController/get', name: 'api_projects_gethostsprojectsoverviewcontroller_get', methods: ['POST'])]
    public function get(int $userId)
    {
        return $this->getHostProjectsOverview->get($userId);
    }
}
