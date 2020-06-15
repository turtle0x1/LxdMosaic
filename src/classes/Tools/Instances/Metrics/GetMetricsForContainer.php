<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Model\Metrics\FetchMetrics;
use dhope0000\LXDClient\Model\Metrics\Types\FetchType;

class GetMetricsForContainer
{
    public function __construct(FetchMetrics $fetchMetrics, FetchType $fetchType)
    {
        $this->fetchMetrics = $fetchMetrics;
        $this->fetchType = $fetchType;
    }

    public function getAllTypes($hostId, $container)
    {
        return $this->fetchMetrics->fetchAllTypes($hostId, $container);
    }

    public function getTypeFilters($hostId, $container, $type)
    {
        $allMetrics = $this->fetchMetrics->fetchByHostContainerType($hostId, $container, $type);
        $keys = [];
        foreach ($allMetrics as $metricsIndex => $metricEntry) {
            $data = json_decode($metricEntry["data"], true);
            // We iterate through all the seen entries because the user
            // may have added a new key at some random point at time but this
            // could be way to slow with to much data
            // var_dump(array_keys($data);
            $keys = array_merge($keys, array_keys($data));
        }
        $keys = array_unique($keys);
        asort($keys);
        return array_values($keys);
    }
    public function get($hostId, $container, $type, $filter)
    {
        $allMetrics = $this->fetchMetrics->fetchByHostContainerType($hostId, $container, $type);

        $output = [];

        foreach ($allMetrics as $metricsIndex => $metricEntry) {
            $data = json_decode($metricEntry["data"], true);

            if ($filter !== "") {
                $found = false;
                foreach ($data as $dataKey => $dataValue) {
                    if ($dataKey == $filter) {
                        $found = true;
                        $data = $dataValue;
                        break;
                    }
                }
                if (!$found) {
                    $data = null;
                }
            }

            if (empty($data)) {
                unset($allMetrics[$metricsIndex]);
                continue;
            }

            $allMetrics[$metricsIndex]["data"] = $data;
        }

        $labels = array_column($allMetrics, "date");
        $data = array_column($allMetrics, "data");
        return [
            "formatBytes"=>$this->fetchType->formatTypeAsBytes($type),
            "labels"=>$labels,
            "data"=>$data
        ];
    }
}
