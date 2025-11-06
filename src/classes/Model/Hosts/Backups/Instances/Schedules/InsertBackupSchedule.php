<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules;

use dhope0000\LXDClient\Model\Database\Database;

class InsertBackupSchedule
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(
        int $userId,
        int $hostId,
        string $instance,
        string $project,
        string $schedule,
        int $strategyId,
        int $retention
    ) {
        $sql = 'INSERT INTO `Instance_Backup_Schedule`
                (
                    `IBS_User_ID`,
                    `IBS_Host_ID`,
                    `IBS_Instance`,
                    `IBS_Project`,
                    `IBS_Schedule_String`,
                    `IBS_BS_ID`,
                    `IBS_Retention`
                ) VALUES (
                    :userId,
                    :hostId,
                    :instance,
                    :project,
                    :schedule,
                    :strategyId,
                    :retention
                );';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':userId' => $userId,
            ':hostId' => $hostId,
            ':instance' => $instance,
            ':project' => $project,
            ':schedule' => $schedule,
            ':strategyId' => $strategyId,
            ':retention' => $retention,
        ]);
        return $do->rowCount() ? true : false;
    }
}
