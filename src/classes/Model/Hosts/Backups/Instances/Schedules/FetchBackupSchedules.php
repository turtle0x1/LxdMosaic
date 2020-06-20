<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules;

use dhope0000\LXDClient\Model\Database\Database;

class FetchBackupSchedules
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetch(int $hostId)
    {
        $sql = "SELECT
                    `IBS_Host_ID` as `hostId`,
                    `IBS_Instance` as `instance`,
                    `IBS_Project` as `project`,
                    `IBS_Schedule_String` as `scheduleString`,
                    `IBS_BS_ID` as `strategyId`
                FROM
                    `Instance_Backup_Schedule`
                WHERE
                    `IBS_Host_ID` = :hostId
                ORDER BY
                    `IBS_ID` ASC
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
