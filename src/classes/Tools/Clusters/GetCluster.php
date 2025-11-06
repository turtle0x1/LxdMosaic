<?php

namespace dhope0000\LXDClient\Tools\Clusters;

class GetCluster
{
    public function __construct(
        private readonly GetAllClusters $getAllClusters
    ) {
    }

    public function get($cluster)
    {
        $clusters = $this->getAllClusters->get();
        return $clusters[$cluster];
    }
}
