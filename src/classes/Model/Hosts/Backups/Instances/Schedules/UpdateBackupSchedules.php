<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules;

use dhope0000\LXDClient\Model\Database\Database;

class UpdateBackupSchedules
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function disableActiveScheds(
        int $userId,
        int $hostId,
        string $instance,
        string $project
    ) {
        $sql = "UPDATE `Instance_Backup_Schedule`
                SET
                    `IBS_Disabled` = 1,
                    `IBS_Disabled_By` = :userId,
                    `IBS_Disabled_Date` = CURRENT_TIMESTAMP
                WHERE
                    `IBS_Host_ID` = :hostId
                AND
                    `IBS_Instance` = :instance
                AND
                    `IBS_Project` = :project
                AND
                    `IBS_Disabled` = 0;";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":hostId"=>$hostId,
            ":instance"=>$instance,
            ":project"=>$project
        ]);
        return $do->rowCount() ? true : false;
    }
}
