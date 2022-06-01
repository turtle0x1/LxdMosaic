<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\GetHostProjectsOverview;

class GetHostsProjectsOverviewController
{
    private $getHostProjectsOverview;

    public function __construct(GetHostProjectsOverview $getHostProjectsOverview)
    {
        $this->getHostProjectsOverview = $getHostProjectsOverview;
    }

    public function get(int $userId)
    {
        return $this->getHostProjectsOverview->get($userId);
    }
}
