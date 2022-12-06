<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteBackupSchedules
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function deleteforHost(int $hostId) :bool
    {
        $sql = "DELETE FROM
                    `Instance_Backup_Schedule`
                WHERE
                    `IBS_Host_ID` = :hostId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId
        ]);
        return $do->rowCount() ? true : false;
    }

    public function deleteforUser(int $userId) :bool
    {
        $sql = "DELETE FROM
                    `Instance_Backup_Schedule`
                WHERE
                    `IBS_User_ID` = :userId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId
        ]);
        return $do->rowCount() ? true : false;
    }
}
