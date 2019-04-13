<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\GetProjectInfo;

class GetProjectInfoController
{
    public function __construct(GetProjectInfo $getProjectInfo)
    {
        $this->getProjectInfo = $getProjectInfo;
    }

    public function get(int $hostId, string $project)
    {
        return $this->getProjectInfo->get($hostId, $project);
    }
}
