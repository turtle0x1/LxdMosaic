<?php

namespace dhope0000\LXDClient\Tools\Backups;

use dhope0000\LXDClient\Model\Backups\FetchBackups;
use dhope0000\LXDClient\Tools\Backups\GetHostInstanceStatusForBackupSet;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class GetBackupsOverview
{
    private FetchBackups $fetchBackups;
    private GetHostInstanceStatusForBackupSet $getHostInstanceStatusForBackupSet;
    private FetchUserDetails $fetchUserDetails;

    public function __construct(
        FetchBackups $fetchBackups,
        GetHostInstanceStatusForBackupSet $getHostInstanceStatusForBackupSet,
        FetchUserDetails $fetchUserDetails
    ) {
        $this->fetchBackups = $fetchBackups;
        $this->getHostInstanceStatusForBackupSet = $getHostInstanceStatusForBackupSet;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function get(int $userId) :array
    {
        if ($this->fetchUserDetails->isAdmin($userId)) {
            $allBackups = $this->fetchBackups->fetchAll();
        } else {
            $allBackups = $this->fetchBackups->fetchBackupsUserCanAccess($userId);
        }

        $properties = $this->getProperties($allBackups);

        $allBackups = $this->getHostInstanceStatusForBackupSet->get($userId, $properties["localBackups"]);

        return [
            "sizeByMonthYear"=>$properties["sizeByMonthYear"],
            "filesByMonthYear"=>$properties["filesByMonthYear"],
            "allBackups"=>$allBackups
        ];
    }

    private function getProperties(array $backups) :array
    {
        $sizeByMonthYear = [];
        $filesByMonthYear = [];

        foreach ($backups as $index => $backup) {
            $date = new \DateTime($backup["storedDateCreated"]);
            $month = $date->format("n");
            $year = $date->format("Y");

            if (!isset($sizeByMonthYear[$year])) {
                $this->createYearArray($sizeByMonthYear, (int) $year);
                $this->createYearArray($filesByMonthYear, (int) $year);
            }


            $filesize = $backup["filesize"];
            // After 6 months or so this  can be removed, most backup should have
            // thier size stored in the database then.
            if ($filesize == 0  && file_exists($backup["localPath"])) {
                $filesize = filesize($backup["localPath"]);
            }

            $backup["filesize"] = $filesize;

            if ($backup["deletedDate"] != null) {
                unset($backups[$index]);
            }

            $sizeByMonthYear[$year][$month] += $filesize;
            $filesByMonthYear[$year][$month] ++;
        }

        return [
            "sizeByMonthYear"=>$sizeByMonthYear,
            "filesByMonthYear"=>$filesByMonthYear,
            "localBackups"=>$backups
        ];
    }

    private function createYearArray(array &$array, int $year) :void
    {
        $array[$year] = array_fill(1, 12, null);
    }
}
