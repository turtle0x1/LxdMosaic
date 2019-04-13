<?php

namespace dhope0000\LXDClient\Tools\Hosts\GPU;

use dhope0000\LXDClient\Tools\Hosts\GetResources;

class GetAll
{
    public function __construct(GetResources $getResources)
    {
        $this->getResources = $getResources;
    }

    public function getAll(int $hostId)
    {
        $x = $this->getResources->getHostExtended($hostId);
        if ($x["extensions"]["resGpu"]) {
            return $x["gpu"]["cards"];
        }

        return [];
    }
}
