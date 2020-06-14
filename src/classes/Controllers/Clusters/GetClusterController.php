<?php

namespace dhope0000\LXDClient\Controllers\Clusters;

use dhope0000\LXDClient\Tools\Clusters\GetCluster;

class GetClusterController
{
    public function __construct(GetCluster $getCluster)
    {
        $this->getCluster = $getCluster;
    }

    public function get($cluster)
    {
        return $this->getCluster->get($cluster);
    }
}
