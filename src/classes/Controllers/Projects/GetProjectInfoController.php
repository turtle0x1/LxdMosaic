<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\GetProjectInfo;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetProjectInfoController
{
    private $getProjectInfo;
    
    public function __construct(GetProjectInfo $getProjectInfo)
    {
        $this->getProjectInfo = $getProjectInfo;
    }

    /**
     * @Route("/api/Projects/GetProjectInfoController/get", name="api_projects_getprojectinfocontroller_get", methods={"POST"})
     */
    public function get(Host $host, string $project)
    {
        return $this->getProjectInfo->get($host, $project);
    }
}
