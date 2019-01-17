<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\GetProjectInfo;

class GetProjectInfoController
{
    public function __construct(GetProjectInfo $getProjectInfo)
    {
        $this->getProjectInfo = $getProjectInfo;
    }

    public function get(string $host, string $project){
        return $this->getProjectInfo->get($host, $project);
    }
}
