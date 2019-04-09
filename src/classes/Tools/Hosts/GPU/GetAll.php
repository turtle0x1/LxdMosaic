<?php

namespace dhope0000\LXDClient\Tools\Hosts\GPU;

use dhope0000\LXDClient\Model\Hosts\GetResources;

class GetAll
{
    public function __construct(GetResources $getResources)
    {
        $this->getResources = $getResources;
    }

    public function getAll(string $hostIp)
    {
        $x = $this->getResources->getHostExtended($hostIp);
        if ($x["extensions"]["resGpu"]) {
            return $x["gpu"]["cards"];
        }

        return [];
    }
}
