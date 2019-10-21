<?php

namespace dhope0000\LXDClient\Model\Backups;

use dhope0000\LXDClient\Model\Database\Database;

class FetchBackups
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll()
    {
        $sql = "SELECT
                    `CB_Date_Created` as `storedDateCreated`,
                    `CB_Backup_Date_Created` as `backupDateCreated`,
                    `CB_Host_ID` as `hostId`,
                    `CB_Container` as `container`,
                    `CB_Backup` as `name`,
                    `CB_Local_Path` as `localPath`
                FROM
                `Container_Backups`
                ORDER BY
                    `storedDateCreated` ASC
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
