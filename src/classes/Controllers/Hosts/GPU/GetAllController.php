<?php

namespace dhope0000\LXDClient\Controllers\Hosts\GPU;

use dhope0000\LXDClient\Tools\Hosts\GPU\GetAll;

class GetAllController
{
    public function __construct(GetAll $getAll)
    {
        $this->getAll = $getAll;
    }

    public function getAll(string $hostIp)
    {
        return $this->getAll->getAll($hostIp);
    }
}
