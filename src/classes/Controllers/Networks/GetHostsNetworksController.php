<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\GetHostsNetworks;

class GetHostsNetworksController
{
    public function __construct(GetHostsNetworks $getHostsNetworks)
    {
        $this->getHostsNetworks = $getHostsNetworks;
    }

    public function get(){
        return $this->getHostsNetworks->getAll();
    }
}
