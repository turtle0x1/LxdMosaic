<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\GetMetricsForContainer;
use Symfony\Component\Routing\Attribute\Route;

class GetGraphDataController
{
    public function __construct(
        private readonly GetMetricsForContainer $getMetricsForContainer
    ) {
    }

    #[Route(path: '/api/Instances/Metrics/GetGraphDataController/getAllTypes', name: 'Get graph data types', methods: ['POST'])]
    public function getAllTypes(int $hostId, string $container)
    {
        return $this->getMetricsForContainer->getAllTypes($hostId, $container);
    }

    #[Route(path: '/api/Instances/Metrics/GetGraphDataController/getTypeFilters', name: 'Get graph data filters', methods: ['POST'])]
    public function getTypeFilters(int $hostId, string $container, int $type)
    {
        return $this->getMetricsForContainer->getTypeFilters($hostId, $container, $type);
    }

    #[Route(path: '/api/Instances/Metrics/GetGraphDataController/get', name: 'Get graph data', methods: ['POST'])]
    public function get(int $hostId, string $container, int $type, string $filter, string $range)
    {
        return $this->getMetricsForContainer->get($hostId, $container, $type, $filter, $range);
    }
}
