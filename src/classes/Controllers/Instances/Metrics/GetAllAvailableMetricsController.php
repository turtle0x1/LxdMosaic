<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\GetAvailableHostsMetrics;
use Symfony\Component\Routing\Annotation\Route;

class GetAllAvailableMetricsController
{
    private $getAvailableHostsMetrics;
    
    public function __construct(GetAvailableHostsMetrics $getAvailableHostsMetrics)
    {
        $this->getAvailableHostsMetrics = $getAvailableHostsMetrics;
    }

    /**
     * @Route("/api/Instances/Metrics/GetAllAvailableMetricsController/get", name="api_instances_metrics_getallavailablemetricscontroller_get", methods={"POST"})
     */
    public function get()
    {
        return $this->getAvailableHostsMetrics->get();
    }
}
