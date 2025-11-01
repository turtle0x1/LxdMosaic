<?php

namespace dhope0000\LXDClient\Controllers\Projects\Search;

use dhope0000\LXDClient\Tools\Projects\Search\GetCommonToHostsProjects;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class GetCommonToHostsProjectsController
{
    private $getCommonToHostsProjects;
    
    public function __construct(GetCommonToHostsProjects $getCommonToHostsProjects)
    {
        $this->getCommonToHostsProjects = $getCommonToHostsProjects;
    }

    /**
     * @Route("/api/Projects/Search/GetCommonToHostsProjectsController/get", name="api_projects_search_getcommontohostsprojectscontroller_get", methods={"POST"})
     */
    public function get(int $userId, HostsCollection $hosts)
    {
        return $this->getCommonToHostsProjects->get($userId, $hosts);
    }
}
