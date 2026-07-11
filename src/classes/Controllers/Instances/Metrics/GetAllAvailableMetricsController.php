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

    #[Route(path: '/api/Instances/Metrics/GetAllAvailableMetricsController/get', name: 'Get all available metrics', methods: ['POST'])]
    public function get()
    {
        return $this->getAvailableHostsMetrics->get();
    }
}
