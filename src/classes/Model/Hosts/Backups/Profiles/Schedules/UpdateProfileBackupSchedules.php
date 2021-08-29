<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Profiles\Schedules;

use dhope0000\LXDClient\Model\Database\Database;

class UpdateProfileBackupSchedules
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function disableActiveScheds(
        int $userId,
        int $hostId,
        string $project
    ) {
        $sql = "UPDATE `Profile_Backup_Schedule`
                SET
                    `PBS_Disabled` = 1,
                    `PBS_Disabled_By` = :userId,
                    `PBS_Disabled_Date` = CURRENT_TIMESTAMP
                WHERE
                    `PBS_Host_ID` = :hostId
                AND
                    `PBS_Project` = :project
                AND
                    `PBS_Disabled` = 0;";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":hostId"=>$hostId,
            ":project"=>$project
        ]);
        return $do->rowCount() ? true : false;
    }
}
