<?php

namespace dhope0000\LXDClient\Controllers\Hosts\GPU;

use dhope0000\LXDClient\Tools\Hosts\GPU\GetAll;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetAllController
{
    private $getAll;
    
    public function __construct(GetAll $getAll)
    {
        $this->getAll = $getAll;
    }

    /**
     * @Route("/api/Hosts/GPU/GetAllController/getAll", name="api_hosts_gpu_getallcontroller_getall", methods={"POST"})
     */
    public function getAll(Host $host)
    {
        return $this->getAll->getAll($host);
    }
}
