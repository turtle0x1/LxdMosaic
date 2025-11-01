<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\GetMetricsForContainer;
use Symfony\Component\Routing\Annotation\Route;

class GetGraphDataController
{
    private $getMetricsForContainer;
    
    public function __construct(GetMetricsForContainer $getMetricsForContainer)
    {
        $this->getMetricsForContainer = $getMetricsForContainer;
    }

    /**
     * @Route("/api/Instances/Metrics/GetGraphDataController/getAllTypes", name="api_instances_metrics_getgraphdatacontroller_getalltypes", methods={"POST"})
     */
    public function getAllTypes(int $hostId, string $container)
    {
        return $this->getMetricsForContainer->getAllTypes($hostId, $container);
    }

    /**
     * @Route("/api/Instances/Metrics/GetGraphDataController/getTypeFilters", name="api_instances_metrics_getgraphdatacontroller_gettypefilters", methods={"POST"})
     */
    public function getTypeFilters(int $hostId, string $container, int $type)
    {
        return $this->getMetricsForContainer->getTypeFilters($hostId, $container, $type);
    }

    /**
     * @Route("/api/Instances/Metrics/GetGraphDataController/get", name="api_instances_metrics_getgraphdatacontroller_get", methods={"POST"})
     */
    public function get(int $hostId, string $container, int $type, string $filter, string $range)
    {
        return $this->getMetricsForContainer->get($hostId, $container, $type, $filter, $range);
    }
}
