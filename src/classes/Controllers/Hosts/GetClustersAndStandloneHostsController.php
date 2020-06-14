<?php

namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;

class GetClustersAndStandloneHostsController
{
    public function __construct(GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts)
    {
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
    }

    public function get()
    {
        return $this->getClustersAndStandaloneHosts->get();
    }
}
