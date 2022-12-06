<?php

namespace dhope0000\LXDClient\Tools\Backups\Schedule;

use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\FetchBackupSchedules;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\UpdateBackupSchedules;

class RemoveDeadBackupSchedules
{
    private FetchBackupSchedules $fetchBackupSchedules;
    private GetDetails $getDetails;
    private UpdateBackupSchedules $updateBackupSchedules;

    public function __construct(
        FetchBackupSchedules $fetchBackupSchedules,
        GetDetails $getDetails,
        UpdateBackupSchedules $updateBackupSchedules
    ) {
        $this->fetchBackupSchedules = $fetchBackupSchedules;
        $this->getDetails = $getDetails;
        $this->updateBackupSchedules = $updateBackupSchedules;
    }

    public function remove() :void
    {
        $hostBackups = $this->fetchBackupSchedules->fetchActiveSchedsGroupedByHostId();
        foreach ($hostBackups as $hostId => $backups) {
            $hostProjectSchedules = [];
            foreach ($backups as $backup) {
                if (!isset($hostProjectSchedules[$backup["project"]])) {
                    $hostProjectSchedules[$backup["project"]] = [];
                }
                $hostProjectSchedules[$backup["project"]][] = $backup;
            }


            $host = $this->getDetails->fetchHost($hostId);


            foreach ($hostProjectSchedules as $project => $schedules) {
                $instances = [];

                if ($host->hostOnline()) {
                    $host->setProject($project);
                    $instances = $host->instances->all();
                }

                foreach ($schedules as $schedule) {
                    if (!in_array($schedule["instance"], $instances)) {
                        $this->updateBackupSchedules->disableActiveScheds(
                            1, // Admin user id
                            $hostId,
                            $schedule["instance"],
                            $project
                        );
                    }
                }
            }
        }
    }
}
