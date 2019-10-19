<?php

namespace dhope0000\LXDClient\Controllers\Clusters;

use dhope0000\LXDClient\Tools\Clusters\GetAllClusters;

class GetAllController
{
    public function __construct(GetAllClusters $getAllClusters)
    {
        $this->getAllClusters = $getAllClusters;
    }

    public function get()
    {
        return  $this->getAllClusters->get();
    }
}
