<?php

namespace dhope0000\LXDClient\Model\Backups;

use dhope0000\LXDClient\Model\Database\Database;

class FetchBackup
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetch(int $backupId)
    {
        $sql = "SELECT
                    `CB_ID` as `id`,
                    `CB_Date_Created` as `storedDateCreated`,
                    `CB_Backup_Date_Created` as `backupDateCreated`,
                    `CB_Host_ID` as `hostId`,
                    `CB_Container` as `container`,
                    `CB_Backup` as `name`,
                    `CB_Local_Path` as `localPath`,
                    `CB_Project` as `project`
                FROM
                    `Container_Backups`
                WHERE
                    `CB_ID` = :backupId
                ORDER BY
                    `storedDateCreated` ASC
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":backupId"=>$backupId
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }
}
