<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\GetNetwork;

class GetNetworkController
{
    public function __construct(GetNetwork $getNetwork)
    {
        $this->getNetwork = $getNetwork;
    }

    public function get(int $hostId, $network){
        return $this->getNetwork->get($hostId, $network);
    }
}
