<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Profiles\Schedules;

use dhope0000\LXDClient\Model\Database\Database;

class FetchProfilesBackupSchedules
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchActiveSchedsGroupedByHostId()
    {
        $sql = "SELECT
                    `PBS_Host_ID` as `hostId`,
                    `PBS_Project` as `project`,
                    `PBS_Schedule_String` as `scheduleString`,
                    `PBS_Stratergy_ID` as `strategyId`,
                    `PBS_Retention` as `scheduleRetention`,
                    `Profile_Backup_Strategies`.`PBS_Name` as `strategyName`
                FROM
                    `Profile_Backup_Schedule`
                LEFT JOIN `Profile_Backup_Strategies` ON
                    `PBS_Stratergy_ID` = `Profile_Backup_Strategies`.`PBS_ID`
                WHERE
                    `PBS_Disabled` = 0
                ORDER BY
                    `Profile_Backup_Schedule`.`PBS_ID` ASC
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
