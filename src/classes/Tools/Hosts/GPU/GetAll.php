<?php

namespace dhope0000\LXDClient\Tools\Hosts\GPU;

use dhope0000\LXDClient\Tools\Hosts\GetResources;
use dhope0000\LXDClient\Objects\Host;

class GetAll
{
    private GetResources $getResources;

    public function __construct(GetResources $getResources)
    {
        $this->getResources = $getResources;
    }

    public function getAll(Host $host)
    {
        $x = $this->getResources->getHostExtended($host);
        if ($x["extensions"]["resGpu"]) {
            return $x["gpu"]["cards"];
        }

        return [];
    }
}
