<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\GetAvailableHostsMetrics;
use Symfony\Component\Routing\Annotation\Route;

class GetAllAvailableMetricsController
{
    public function __construct(GetAvailableHostsMetrics $getAvailableHostsMetrics)
    {
        $this->getAvailableHostsMetrics = $getAvailableHostsMetrics;
    }
    /**
     * @Route("/api/Instances/Metrics/GetAllAvailableMetricsController/get", methods={"POST"}, name="Get all available metrics")
     */
    public function get()
    {
        return $this->getAvailableHostsMetrics->get();
    }
}
