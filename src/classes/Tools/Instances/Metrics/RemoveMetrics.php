<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Model\Metrics\FetchMetrics;
use dhope0000\LXDClient\Model\Metrics\InsertMetric;
use dhope0000\LXDClient\Model\Metrics\DeleteMetrics;

class RemoveMetrics
{
    private $fetchMetrics;
    private $insertMetric;
    private $deleteMetrics;

    public function __construct(
        FetchMetrics $fetchMetrics,
        InsertMetric $insertMetric,
        DeleteMetrics $deleteMetrics
    ) {
        $this->fetchMetrics = $fetchMetrics;
        $this->insertMetric = $insertMetric;
        $this->deleteMetrics = $deleteMetrics;
    }

    public function remove()
    {
        $metrics = $this->fetchMetrics->fetchGroupedByFiveMinutes();
        $groupedByHost = $this->groupMetrics($metrics);
        $result = $this->averageGrouped($groupedByHost);
        
        if (!empty($result["toDelete"])) {
            $this->deleteMetrics->deleteByIds($result["toDelete"]);
        }

        $this->insertMetrics($result["toInsert"]);
    }

    public function insertMetrics(array $toInsert)
    {
        foreach ($toInsert as $metric) {
            $this->insertMetric->insert(
                $metric[0],
                $metric[1],
                $metric[2],
                $metric[3],
                $metric[4]
            );
        }
    }

    public function groupMetrics($metrics)
    {
        $groupedByHost = [];
        foreach ($metrics as $metric) {
            $hostId = $metric["hostId"];
            $project = $metric["project"];
            $instance = $metric["instance"];
            $typeId = $metric["typeId"];
            $dTime = $metric["dTime"];

            if (!isset($groupedByHost[$hostId])) {
                $groupedByHost[$hostId] = [];
            }

            if (!isset($groupedByHost[$hostId][$project])) {
                $groupedByHost[$hostId][$project] = [];
            }

            if (!isset($groupedByHost[$hostId][$project][$instance])) {
                $groupedByHost[$hostId][$project][$instance] = [];
            }

            if (!isset($groupedByHost[$hostId][$project][$instance][$typeId])) {
                $groupedByHost[$hostId][$project][$instance][$typeId] = [];
            }

            if (!isset($groupedByHost[$hostId][$project][$instance][$typeId][$dTime])) {
                $groupedByHost[$hostId][$project][$instance][$typeId][$dTime] = [];
            }
            $groupedByHost[$hostId][$project][$instance][$typeId][$dTime][$metric["id"]] = json_decode($metric["data"], true);
        }
        return $groupedByHost;
    }

    public function averageGrouped(array $groupedByHost)
    {
        $idsToDelete = [];
        $toInsert = [];
        foreach ($groupedByHost as $hostId => $projects) {
            foreach ($projects as $project => $instances) {
                foreach ($instances as $instance => $types) {
                    foreach ($types as $typeId => $dTimes) {
                        foreach ($dTimes as $dTime => $metrics) {
                            $noEntries = count($metrics);
                            // Already been averaged no point re-averaging
                            if ($noEntries == 1) {
                                continue;
                            }

                            $keys = [];

                            foreach ($metrics as $id => $data) {
                                $idsToDelete[] = $id;
                                foreach ($data as $key=>$value) {
                                    if (!isset($keys[$key])) {
                                        $keys[$key] = 0;
                                    }
                                    $keys[$key] += $value;
                                }
                            }

                            $periodAverages = [];

                            foreach ($keys as $key => $totalValue) {
                                $periodAverages[$key] = $totalValue / $noEntries;
                            }

                            // The values are (18:30, 18:31, 18:32, 18:33, 18:34)
                            $newDate = (new \DateTime($dTime))->modify("+5 minutes");

                            // Inserting in a loop is slow should probably try to batch
                            $toInsert[] = [
                                $newDate->format("Y-m-d H:i:s"),
                                $hostId,
                                $instance,
                                $typeId,
                                json_encode($periodAverages)
                            ];
                        }
                    }
                }
            }
        }
        return ["toDelete"=>$idsToDelete, "toInsert"=>$toInsert];
    }
}
