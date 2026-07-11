<?php

namespace dhope0000\LXDClient\Controllers\Projects\Search;

use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Projects\Search\GetCommonToHostsProjects;
use Symfony\Component\Routing\Attribute\Route;

class GetCommonToHostsProjectsController
{
    public function __construct(
        private readonly GetCommonToHostsProjects $getCommonToHostsProjects
    ) {
    }

    #[Route(path: '/api/Projects/Search/GetCommonToHostsProjectsController/get', name: 'Get common projects', methods: ['POST'])]
    public function get(int $userId, HostsCollection $hosts)
    {
        return $this->getCommonToHostsProjects->get($userId, $hosts);
    }
}
