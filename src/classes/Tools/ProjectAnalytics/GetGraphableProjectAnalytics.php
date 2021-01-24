<?php

namespace dhope0000\LXDClient\Tools\ProjectAnalytics;

use dhope0000\LXDClient\Model\ProjectAnalytics\FetchAnalytics;

class GetGraphableProjectAnalytics
{
    private $fetchAnalytics;

    public function __construct(
        FetchAnalytics $fetchAnalytics
    ) {
        $this->fetchAnalytics = $fetchAnalytics;
    }

    public function getCurrent()
    {
        $startDate = (new \DateTime())->modify("-30 minutes");
        $endDate = (new \DateTime());

        $enteries = $this->fetchAnalytics->fetchBetween($startDate, $endDate);
        return $this->groupEnteries($enteries);
    }

    private function groupEnteries(array $entries)
    {
        $output = [];
        foreach ($entries as $entry) {
            $alias = $entry["hostAlias"];
            $project = $entry["project"];
            $type = $entry["typeName"];

            if (!isset($output[$alias])) {
                $output[$alias] = [];
            }

            if (!isset($output[$alias][$project])) {
                $output[$alias][$project] = [];
            }

            if (!isset($output[$alias][$project][$type])) {
                $output[$alias][$project][$type] = [];
            }

            $output[$alias][$project][$type][] = [
                "created"=>$entry["created"],
                "value"=>$entry["value"],
                "limit"=>$entry["limit"]
            ];
        }
        return $output;
    }
}
