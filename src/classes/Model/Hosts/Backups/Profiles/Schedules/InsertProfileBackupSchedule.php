<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Profiles\Schedules;

use dhope0000\LXDClient\Model\Database\Database;

class InsertProfileBackupSchedule
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(
        int $userId,
        int $hostId,
        string $project,
        string $schedule,
        int $strategyId,
        int $retention
    ) {
        $sql = "INSERT INTO `Profile_Backup_Schedule`
                (
                    `PBS_User_ID`,
                    `PBS_Host_ID`,
                    `PBS_Project`,
                    `PBS_Schedule_String`,
                    `PBS_Stratergy_ID`,
                    `PBS_Retention`
                ) VALUES (
                    :userId,
                    :hostId,
                    :project,
                    :schedule,
                    :strategyId,
                    :retention
                );";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":hostId"=>$hostId,
            ":project"=>$project,
            ":schedule"=>$schedule,
            ":strategyId"=>$strategyId,
            ":retention"=>$retention
        ]);
        return $do->rowCount() ? true : false;
    }
}
