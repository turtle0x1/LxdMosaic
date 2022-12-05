<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules;

use dhope0000\LXDClient\Model\Database\Database;

class FetchBackupSchedules
{
    private \PDO $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchActiveSchedsGroupedByHostId()
    {
        $sql = "SELECT
                    `IBS_Host_ID` as `hostId`,
                    `IBS_Instance` as `instance`,
                    `IBS_Project` as `project`,
                    `IBS_Schedule_String` as `scheduleString`,
                    `IBS_BS_ID` as `strategyId`,
                    `IBS_Retention` as `scheduleRetention`,
                    `Backup_Strategies`.`BS_Name` as `strategyName`
                FROM
                    `Instance_Backup_Schedule`
                LEFT JOIN `Backup_Strategies` ON
                    `Instance_Backup_Schedule`.`IBS_BS_ID` = `Backup_Strategies`.`BS_ID`
                WHERE
                    `IBS_Disabled` = 0
                ORDER BY
                    `IBS_ID` ASC
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_GROUP|\PDO::FETCH_ASSOC);
    }

    public function fetchActive(int $hostId)
    {
        $sql = "SELECT
                    `IBS_Host_ID` as `hostId`,
                    `IBS_Instance` as `instance`,
                    `IBS_Project` as `project`,
                    `IBS_Schedule_String` as `scheduleString`,
                    `IBS_BS_ID` as `strategyId`,
                    `IBS_Retention` as `scheduleRetention`,
                    `Backup_Strategies`.`BS_Name` as `strategyName`
                FROM
                    `Instance_Backup_Schedule`
                LEFT JOIN `Backup_Strategies` ON
                    `Instance_Backup_Schedule`.`IBS_BS_ID` = `Backup_Strategies`.`BS_ID`
                WHERE
                    `IBS_Host_ID` = :hostId
                AND
                    `IBS_Disabled` = 0
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
