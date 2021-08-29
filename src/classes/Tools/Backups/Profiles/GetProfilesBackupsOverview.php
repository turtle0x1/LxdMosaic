<?php

namespace dhope0000\LXDClient\Tools\Backups\Profiles;

use dhope0000\LXDClient\Model\Backups\Profiles\FetchProfileBackups;
use dhope0000\LXDClient\Tools\Backups\Profiles\GetHostProfileStatusForBackupSet;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class GetProfilesBackupsOverview
{
    private $fetchProfileBackups;

    public function __construct(
        FetchProfileBackups $fetchProfileBackups,
        GetHostProfileStatusForBackupSet $getHostProfileStatusForBackupSet,
        FetchUserDetails $fetchUserDetails
    ) {
        $this->fetchProfileBackups = $fetchProfileBackups;
        $this->getHostProfileStatusForBackupSet = $getHostProfileStatusForBackupSet;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function get($userId)
    {
        if ($this->fetchUserDetails->isAdmin($userId)) {
            $allBackups = $this->fetchProfileBackups->fetchAll();
        } else {
            $allBackups = $this->fetchProfileBackups->fetchProfileBackupsUserCanAccess($userId);
        }

        $properties = $this->getProperties($allBackups);

        $allBackups = $this->getHostProfileStatusForBackupSet->get($userId, $properties["localBackups"]);

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

    private function createYearArray(&$array, $year)
    {
        $array[$year] = array_fill(1, 12, null);
    }
}
