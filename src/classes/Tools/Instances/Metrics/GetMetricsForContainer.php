<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Model\Metrics\FetchMetrics;
use dhope0000\LXDClient\Model\Metrics\Types\FetchType;
use dhope0000\LXDClient\Tools\Utilities\DateTools;

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
        $startDate = (new \DateTime)->modify("-6 months");
        $allMetrics = $this->fetchMetrics->fetchByHostContainerType($hostId, $container, $type, $startDate);
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

    public function get($hostId, $container, $type, $filter, $range = "P30I")
    {
        $startDate = (new \DateTime())->modify($range);
        $endDate = new \DateTimeImmutable;

        $allMetrics = $this->fetchMetrics->fetchByHostContainerType(
            $hostId,
            $container,
            $type,
            $startDate
        );

        $period = new \DatePeriod(
            $startDate,
            new \DateInterval('P1D'),
            (new \DateTime())->setTime(23, 59, 00)
        );

        $dataByDate = [];

        $numberOfDays = iterator_count($period);
        $moreThanOneDay = $numberOfDays > 1;

        $step = 1;
        if ($numberOfDays > 1 && $numberOfDays < 31) {
            $step = 15;
        } elseif ($numberOfDays > 31) {
            $step = 30;
        }

        foreach ($period as $key => $value) {
            $dateString = $value->format("d-m-Y");

            $startMinute = 0;
            $startHour = 0;
            $stopAtNow = $dateString === $endDate->format("d-m-Y");

            // If this day is the start date
            if ($startDate->format("d-m-Y") === $dateString) {
                $startHour = $startDate->format("H");
                $startMinute = $startDate->format("i");
            }

            $prefix = $moreThanOneDay ?  $dateString . " " : "";

            $dataByDate[$dateString] = DateTools::hoursRange($startHour, 24, $step, $prefix, $stopAtNow, $startMinute);
        }

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
                continue;
            }

            $date = new \DateTimeImmutable($metricEntry["date"]);

            $format = $moreThanOneDay ?  "d-m-Y H:i" : "H:i";

            if (array_key_exists($date->format($format), $dataByDate[$date->format("d-m-Y")])) {
                $dataByDate[$date->format("d-m-Y")][$date->format($format)] = $data;
            }
        }

        $labels = [];

        $outData = [];

        foreach ($dataByDate as $date => $data) {
            $labels = array_merge($labels, array_keys($data));
            $outData = array_merge($outData, array_values($data));
        }
        return [
            "formatBytes"=>$this->fetchType->formatTypeAsBytes($type),
            "labels"=>$labels,
            "data"=>$outData
        ];
    }
}
