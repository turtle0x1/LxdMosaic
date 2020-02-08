<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\GetHostContainerStatus;

class GetAllInstancesSettings
{
    public function __construct(GetHostContainerStatus $getHostContainerStatus)
    {
        $this->getHostContainerStatus = $getHostContainerStatus;
    }

    public function get()
    {
        return $this->getHostContainerStatus->get();
    }
}
