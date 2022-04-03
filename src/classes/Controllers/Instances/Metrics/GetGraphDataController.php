<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\GetMetricsForContainer;
use Symfony\Component\Routing\Annotation\Route;

class GetGraphDataController
{
    public function __construct(GetMetricsForContainer $getMetricsForContainer)
    {
        $this->getMetricsForContainer = $getMetricsForContainer;
    }
    /**
     * @Route("/api/Instances/Metrics/GetGraphDataController/getAllTypes", methods={"POST"}, name="Get all types of metrics for an instance")
     */
    public function getAllTypes(int $hostId, string $container)
    {
        return $this->getMetricsForContainer->getAllTypes($hostId, $container);
    }
    /**
     * @Route("/api/Instances/Metrics/GetGraphDataController/getTypeFilters", methods={"POST"}, name="Get all types filters for instance metrics")
     */
    public function getTypeFilters(int $hostId, string $container, int $type)
    {
        return $this->getMetricsForContainer->getTypeFilters($hostId, $container, $type);
    }
    /**
     * @Route("/api/Instances/Metrics/GetGraphDataController/get", methods={"POST"}, name="Get all filtered type metrics for an instance")
     */
    public function get(int $hostId, string $container, int $type, string $filter, string $range)
    {
        return $this->getMetricsForContainer->get($hostId, $container, $type, $filter, $range);
    }
}
