<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\GetProjectsOverview;
use Symfony\Component\Routing\Annotation\Route;

class GetProjectsOverviewController
{
    public function __construct(
        private readonly GetProjectsOverview $getProjectsOverview
    ) {
    }

    /**
     * @Route("/api/Projects/GetProjectsOverviewController/get", name="api_projects_getprojectsoverviewcontroller_get", methods={"POST"})
     */
    public function get(int $userId)
    {
        return $this->getProjectsOverview->get($userId);
    }
}
