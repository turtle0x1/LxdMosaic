<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\GetProjectsOverview;
use dhope0000\LXDClient\Objects\Host;

class GetProjectsOverviewController
{
    private GetProjectsOverview $getProjectsOverview;

    public function __construct(GetProjectsOverview $getProjectsOverview)
    {
        $this->getProjectsOverview = $getProjectsOverview;
    }

    public function get(int $userId)
    {
        return $this->getProjectsOverview->get($userId);
    }
}
