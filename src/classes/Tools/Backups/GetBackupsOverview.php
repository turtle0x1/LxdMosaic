<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Backups\FetchBackups;
use dhope0000\LXDClient\Tools\Backups\GetHostInstanceStatusForBackupSet;

class GetBackupsOverview
{
    private $fetchBackups;

    public function __construct(
        FetchBackups $fetchBackups,
        GetHostInstanceStatusForBackupSet $getHostInstanceStatusForBackupSet
    ) {
        $this->fetchBackups = $fetchBackups;
        $this->getHostInstanceStatusForBackupSet = $getHostInstanceStatusForBackupSet;
    }

    public function get()
    {
        $allBackups = $this->fetchBackups->fetchAll();
        $properties = $this->getProperties($allBackups);

        $allBackups = $this->getHostInstanceStatusForBackupSet->get($properties["localBackups"]);

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

        foreach ($backups as $index => $backup) {
            $date = new \DateTime($backup["storedDateCreated"]);
            $month = $date->format("n");
            $year = $date->format("Y");

            if (!isset($sizeByMonthYear[$year])) {
                $this->createYearArray($sizeByMonthYear, $year);
                $this->createYearArray($filesByMonthYear, $year);
            }

            $filesize = 0;
            // We should be doing something more agressive in this case!
            if (file_exists($backup["localPath"])) {
                $filesize = filesize($backup["localPath"]);
            }

            $backup["filesize"] = $filesize;

            $backups[$index] = $backup;

            $sizeByMonthYear[$year][$month] += $filesize;
            $filesByMonthYear[$year][$month] ++;
        }

        return [
            "sizeByMonthYear"=>$sizeByMonthYear,
            "filesByMonthYear"=>$filesByMonthYear,
            "localBackups"=>$backups
        ];
    }

    private function createYearArray(&$array, $year)
    {
        $array[$year] = array_fill(1, 12, null);
    }
}
