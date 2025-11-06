<?php

namespace dhope0000\LXDClient\Tools\Hosts\GPU;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\GetResources;

class GetAll
{
    public function __construct(
        private readonly GetResources $getResources
    ) {
    }

    public function getAll(Host $host)
    {
        $x = $this->getResources->getHostExtended($host);
        if ($x['extensions']['resGpu']) {
            return $x['gpu']['cards'];
        }

        return [];
    }
}
