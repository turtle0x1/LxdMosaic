<?php

namespace dhope0000\LXDClient\Controllers\Hosts\GPU;

use dhope0000\LXDClient\Tools\Hosts\GPU\GetAll;
use dhope0000\LXDClient\Objects\Host;

class GetAllController
{
    private $getAll;
    
    public function __construct(GetAll $getAll)
    {
        $this->getAll = $getAll;
    }

    public function getAll(Host $host)
    {
        return $this->getAll->getAll($host);
    }
}
