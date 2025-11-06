<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\GetHostProjectsOverview;
use Symfony\Component\Routing\Annotation\Route;

class GetHostsProjectsOverviewController
{
    public function __construct(
        private readonly GetHostProjectsOverview $getHostProjectsOverview
    ) {
    }

    /**
     * @Route("/api/Projects/GetHostsProjectsOverviewController/get", name="api_projects_gethostsprojectsoverviewcontroller_get", methods={"POST"})
     */
    public function get(int $userId)
    {
        return $this->getHostProjectsOverview->get($userId);
    }
}
