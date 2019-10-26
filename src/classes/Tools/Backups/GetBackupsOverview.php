<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Backups\FetchBackups;
use dhope0000\LXDClient\Tools\Backups\GetHostContainerStatusForBackupSet;

class GetBackupsOverview
{
    private $fetchBackups;

    public function __construct(
        FetchBackups $fetchBackups,
        GetHostContainerStatusForBackupSet $getHostContainerStatusForBackupSet
    ) {
        $this->fetchBackups = $fetchBackups;
        $this->getHostContainerStatusForBackupSet = $getHostContainerStatusForBackupSet;
    }

    public function get()
    {
        $allBackups = $this->fetchBackups->fetchAll();
        $properties = $this->getProperties($allBackups);

        $allBackups = $this->getHostContainerStatusForBackupSet->get($allBackups);

        return [
            "sizeByMonthYear"=>$properties["sizeByMonthYear"],
            "filesByMonthYear"=>$properties["filesByMonthYear"],
            "allBackups"=>$allBackups
        ];
    }

    private function getProperties(array $backups)
    {
        $sizeByMonthYear = [];
        $filesByMonthYear = [];

        $dateTime = new \DateTime;
        $currentYear = $dateTime->format("Y");
        $currentMonth = $dateTime->format("m");

        foreach ($backups as $backup) {
            $date = new \DateTime($backup["storedDateCreated"]);
            $month = $date->format("m");
            $year = $date->format("Y");

            $monthLen = $year == $currentYear ? $currentMonth : 12;

            if (!isset($sizeByMonthYear[$year])) {
                $this->createYearArray($sizeByMonthYear, $year, $monthLen);
                $this->createYearArray($filesByMonthYear, $year, $monthLen);
            }

            $sizeByMonthYear[$year][$month] += filesize($backup["localPath"]);
            $filesByMonthYear[$year][$month] ++;
        }

        return [
            "sizeByMonthYear"=>$sizeByMonthYear,
            "filesByMonthYear"=>$filesByMonthYear
        ];
    }

    private function createYearArray(&$array, $year, $monthLen)
    {
        for ($i = 0; $i <= $monthLen; $i++) {
            $k = $i < 10 ? "0$i" : $i;
            $array[$year][$k] = 0;
        }
    }
}
