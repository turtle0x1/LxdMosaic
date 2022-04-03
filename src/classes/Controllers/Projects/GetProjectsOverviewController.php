<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\GetProjectsOverview;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetProjectsOverviewController
{
    public function __construct(GetProjectsOverview $getProjectsOverview)
    {
        $this->getProjectsOverview = $getProjectsOverview;
    }
    /**
     * @Route("/api/Projects/GetProjectsOverviewController/get", methods={"POST"}, name="Get projects dashboard data")
     */
    public function get(int $userId)
    {
        return $this->getProjectsOverview->get($userId);
    }
}
