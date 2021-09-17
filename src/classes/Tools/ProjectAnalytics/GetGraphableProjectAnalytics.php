<?php

namespace dhope0000\LXDClient\Tools\ProjectAnalytics;

use dhope0000\LXDClient\Model\ProjectAnalytics\FetchAnalytics;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;

class GetGraphableProjectAnalytics
{
    private $fetchAnalytics;
    private $fetchUserDetails;
    private $fetchAllowedProjects;

    public function __construct(
        FetchAnalytics $fetchAnalytics,
        FetchUserDetails $fetchUserDetails,
        FetchAllowedProjects $fetchAllowedProjects
    ) {
        $this->fetchAnalytics = $fetchAnalytics;
        $this->fetchUserDetails = $fetchUserDetails;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
    }

    public function getCurrent(int $userId, string $history = "-30 minutes")
    {
        $startDate = (new \DateTime())->modify($history);
        $endDate = (new \DateTime());

        $enteries = $this->fetchAnalytics->fetchBetween($startDate, $endDate);
        return $this->groupEnteries($userId, $enteries);
    }

    private function groupEnteries(int $userId, array $entries)
    {
        $isAdmin = (bool) $this->fetchUserDetails->isAdmin($userId);
        $allowedProjects = $this->fetchAllowedProjects->fetchAll($userId);

        $output = [];

        $typeTotals = [];

        foreach ($entries as $entry) {
            $alias = $entry["hostAlias"];
            $project = $entry["project"];

            if (!$isAdmin) {
                if (!isset($allowedProjects[$entry["hostId"]])) {
                    continue;
                } elseif (!in_array($project, $allowedProjects[$entry["hostId"]])) {
                    continue;
                }
            }
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

            if (!isset($typeTotals[$type])) {
                $typeTotals[$type] = [];
            }

            if (!isset($typeTotals[$type][$entry["created"]])) {
                $typeTotals[$type][$entry["created"]] = 0;
            }

            $typeTotals[$type][$entry["created"]] += $entry["value"];

            $output[$alias][$project][$type][] = [
                "created"=>$entry["created"],
                "value"=>$entry["value"],
                "limit"=>$entry["limit"]
            ];
        }

        return [
            "totals"=>$typeTotals,
            "projectAnalytics"=>$output
        ];
    }
}
