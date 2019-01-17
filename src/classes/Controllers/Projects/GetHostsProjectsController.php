<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\CreateProject;
use dhope0000\LXDClient\Tools\Projects\GetHostsProjects;

class GetHostsProjectsController
{
    public function __construct(GetHostsProjects $getHostsProjects)
    {
        $this->getHostsProjects = $getHostsProjects;
    }

    public function get(){
        return $this->getHostsProjects->getAll();
    }
}
