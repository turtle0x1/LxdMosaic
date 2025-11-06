<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\GetAvailableHostsMetrics;
use Symfony\Component\Routing\Attribute\Route;

class GetAllAvailableMetricsController
{
    public function __construct(
        private readonly GetAvailableHostsMetrics $getAvailableHostsMetrics
    ) {
    }

    #[Route(path: '/api/Instances/Metrics/GetAllAvailableMetricsController/get', name: 'api_instances_metrics_getallavailablemetricscontroller_get', methods: ['POST'])]
    public function get()
    {
        return $this->getAvailableHostsMetrics->get();
    }
}
