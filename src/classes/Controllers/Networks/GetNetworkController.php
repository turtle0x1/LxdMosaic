<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\GetNetwork;
use dhope0000\LXDClient\Objects\Host;

class GetNetworkController
{
    public function __construct(GetNetwork $getNetwork)
    {
        $this->getNetwork = $getNetwork;
    }

    public function get(Host $host, $network)
    {
        return $this->getNetwork->get($host, $network);
    }
}
