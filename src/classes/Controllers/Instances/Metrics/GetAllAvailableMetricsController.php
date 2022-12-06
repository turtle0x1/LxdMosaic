<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\GetAvailableHostsMetrics;

class GetAllAvailableMetricsController
{
    private GetAvailableHostsMetrics $getAvailableHostsMetrics;

    public function __construct(GetAvailableHostsMetrics $getAvailableHostsMetrics)
    {
        $this->getAvailableHostsMetrics = $getAvailableHostsMetrics;
    }

    public function get()
    {
        return $this->getAvailableHostsMetrics->get();
    }
}
