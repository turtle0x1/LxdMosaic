<?php

namespace dhope0000\LXDClient\Tools\Clusters;

use dhope0000\LXDClient\Tools\Clusters\GetAllClusters;

class GetCluster
{
    private $getAllClusters;

    public function __construct(GetAllClusters $getAllClusters)
    {
        $this->getAllClusters = $getAllClusters;
    }

    public function get($cluster)
    {
        $clusters = $this->getAllClusters->get();
        return $clusters[$cluster];
    }
}
