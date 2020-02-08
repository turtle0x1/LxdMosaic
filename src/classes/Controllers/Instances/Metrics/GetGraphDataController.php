<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\GetMetricsForContainer;

class GetGraphDataController
{
    public function __construct(GetMetricsForContainer $getMetricsForContainer)
    {
        $this->getMetricsForContainer = $getMetricsForContainer;
    }

    public function getAllTypes(int $hostId, string $container)
    {
        return $this->getMetricsForContainer->getAllTypes($hostId, $container);
    }

    public function getTypeFilters(int $hostId, string $container, int $type)
    {
        return $this->getMetricsForContainer->getTypeFilters($hostId, $container, $type);
    }

    public function get(int $hostId, string $container, int $type, string $filter)
    {
        return $this->getMetricsForContainer->get($hostId, $container, $type, $filter);
    }
}
