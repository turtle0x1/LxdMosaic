<?php

namespace dhope0000\LXDClient\Controllers\Hosts\GPU;

use dhope0000\LXDClient\Tools\Hosts\GPU\GetAll;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetAllController
{
    public function __construct(GetAll $getAll)
    {
        $this->getAll = $getAll;
    }
    /**
     * @Route("/api/Hosts/GPU/GetAllController/getAll", methods={"POST"}, name="Get all GPU's on a host", options={"rbac" = "hosts.read"})
     */
    public function getAll(Host $host)
    {
        return $this->getAll->getAll($host);
    }
}
