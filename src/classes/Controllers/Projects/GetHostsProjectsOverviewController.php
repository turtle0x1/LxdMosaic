<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\GetHostProjectsOverview;
use Symfony\Component\Routing\Annotation\Route;

class GetHostsProjectsOverviewController
{
    public function __construct(GetHostProjectsOverview $getHostProjectsOverview)
    {
        $this->getHostProjectsOverview = $getHostProjectsOverview;
    }
    /**
     * @Route("/api/Projects/GetHostsProjectsOverviewController/get", methods={"POST"}, name="Get projects admin dashboard data")
     */
    public function get(int $userId)
    {
        return $this->getHostProjectsOverview->get($userId);
    }
}
