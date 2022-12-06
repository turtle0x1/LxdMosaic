<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Model\Metrics\FetchMetrics;

class GetAvailableHostsMetrics
{
    private FetchMetrics $fetchMetrics;

    public function __construct(FetchMetrics $fetchMetrics)
    {
        $this->fetchMetrics = $fetchMetrics;
    }

    public function get() :array
    {
        $hosts = $this->fetchMetrics->fetchAvailableMetricsByHost();
        $output = array_fill_keys(array_keys($hosts), ["instances"=>[]]);
        foreach ($hosts as $hostAlias => $types) {
            $output[$hostAlias]["hostId"] = $types[0]["hostId"];
            foreach ($types as $type) {
                if (!isset($output[$hostAlias]["instances"][$type["instance"]])) {
                    $output[$hostAlias]["instances"][$type["instance"]] = [];
                }
                $output[$hostAlias]["instances"][$type["instance"]][] = [
                    "metric"=>$type["metric"],
                    "metricId"=>$type["metricId"]
                ];
            }
        }

        foreach ($output as $hostAlias => &$details) {
            ksort($details["instances"]);
        }

        ksort($output);

        return $output;
    }
}
