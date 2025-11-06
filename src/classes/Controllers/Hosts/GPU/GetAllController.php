<?php

namespace dhope0000\LXDClient\Controllers\Hosts\GPU;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\GPU\GetAll;
use Symfony\Component\Routing\Attribute\Route;

class GetAllController
{
    public function __construct(
        private readonly GetAll $getAll
    ) {
    }

    #[Route(path: '/api/Hosts/GPU/GetAllController/getAll', name: 'api_hosts_gpu_getallcontroller_getall', methods: ['POST'])]
    public function getAll(Host $host)
    {
        return $this->getAll->getAll($host);
    }
}
