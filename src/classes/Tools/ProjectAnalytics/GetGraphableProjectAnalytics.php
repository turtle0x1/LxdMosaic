<?php

namespace dhope0000\LXDClient\Tools\ProjectAnalytics;

use dhope0000\LXDClient\Model\ProjectAnalytics\FetchAnalytics;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Tools\Utilities\DateTools;
use dhope0000\LXDClient\Objects\Host;

class GetGraphableProjectAnalytics
{
    private $fetchAnalytics;
    private $fetchUserDetails;
    private $fetchAllowedProjects;
    private $dateTools;

    public function __construct(
        FetchAnalytics $fetchAnalytics,
        FetchUserDetails $fetchUserDetails,
        FetchAllowedProjects $fetchAllowedProjects,
        DateTools $dateTools
    ) {
        $this->fetchAnalytics = $fetchAnalytics;
        $this->fetchUserDetails = $fetchUserDetails;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->dateTools = $dateTools;
    }

    public function getCurrent(int $userId, string $history = "-30 minutes", ?Host $hostFilter = null)
    {
        $startDate = (new \DateTime())->modify($history);
        $endDate = (new \DateTime());
        $rangeTemplate = $this->getDateInternval($startDate, $endDate);
        // Reconstruct the first date to account for rounding done when
        // setting up the range template
        $startDate = new \DateTimeImmutable(array_keys($rangeTemplate)[0]);

        $enteries = $this->fetchAnalytics->fetchBetween($startDate, $endDate);
        return $this->groupEnteries($userId, $enteries, $rangeTemplate, $hostFilter);
    }

    private function getDateInternval($start, $end)
    {
        $diff = ($start)->diff($end);

        $numberOfDays = (int) $diff->format("%R%a");
        $numberOfHours = (int) $diff->h;

        $step = 5;
        if ($numberOfDays == 0 && $numberOfHours >= 3 && $numberOfHours < 6) {
            $step = 15;
        } elseif ($numberOfDays == 0 && $numberOfHours >= 6) {
            $step = 30;
        } elseif ($numberOfDays == 1) {
            $step = 60;
        }

        $dataByDate = [];

        $period = new \DatePeriod(
            $start,
            new \DateInterval('P1D'),
            $end->setTime(23, 59)
        );

        foreach ($period as $key => $value) {
            $dateString = $value->format("Y-m-d");

            if ($numberOfDays > 1) {
                if ($numberOfDays <= 7) {
                    $dataByDate[$dateString . " " . "08:00"] = null;
                    $dataByDate[$dateString . " " . "12:00"] = null;
                    $dataByDate[$dateString . " " . "18:00"] = null;
                } else {
                    $dataByDate[$dateString. " " . "12:00"] = null;
                }
                continue;
            }

            $startMinute = 0;
            $startHour = 0;
            $stopAtNow = $dateString === $period->getEndDate()->format("Y-m-d");

            // If this day is the start date
            if ($period->getStartDate()->format("Y-m-d") === $dateString) {
                $startHour = $period->getStartDate()->format("H");

                $startMinute = $period->getStartDate()->format("i");

                // round down to the nearest point
                $startMinute = floor($startMinute / $step) * $step;
            }

            $points = $this->dateTools->hoursRange($startHour, 24, $step, "", $stopAtNow, $startMinute);

            foreach ($points as $time=>$value) {
                $dataByDate[$dateString . " " . $time] = $value;
            }
        }
        return $dataByDate;
    }

    private function groupEnteries(int $userId, array $entries, $rangeTemplate, Host $hostFilter = null)
    {
        $isAdmin = (bool) $this->fetchUserDetails->isAdmin($userId);
        $allowedProjects = $this->fetchAllowedProjects->fetchAll($userId);

        $output = [];

        $typeTotals = [];

        foreach ($entries as $entry) {
            if ($hostFilter !== null && $entry["hostId"] != $hostFilter->getHostId()) {
                continue;
            }
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
                $output[$alias][$project][$type] = $rangeTemplate;
            }

            if (!isset($typeTotals[$type])) {
                $typeTotals[$type] = $rangeTemplate;
            }

            $created = (new \DateTime($entry["created"]))->format("Y-m-d H:i");
            // Slower than isset() but because the values are null isset()
            // cant be used
            if (!array_key_exists($created, $typeTotals[$type])) {
                continue;
            }

            $typeTotals[$type][$created] += $entry["value"];

            $output[$alias][$project][$type][$created] = [
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
