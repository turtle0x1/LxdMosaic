<?php

namespace dhope0000\LXDClient\Controllers\Projects\Search;

use dhope0000\LXDClient\Tools\Projects\Search\GetCommonToHostsProjects;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class GetCommonToHostsProjectsController
{
    public function __construct(GetCommonToHostsProjects $getCommonToHostsProjects)
    {
        $this->getCommonToHostsProjects = $getCommonToHostsProjects;
    }
    /**
     * @Route("/api/Projects/Search/GetCommonToHostsProjectsController/get", methods={"POST"}, name="Get list of projects on all selected hosts")
     */
    public function get(int $userId, HostsCollection $hosts)
    {
        return $this->getCommonToHostsProjects->get($userId, $hosts);
    }
}
