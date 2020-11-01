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
                ORDER BY
                    `storedDateCreated` DESC
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchBackupsUserCanAccess(int $userId)
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
                    `CB_Project` IN(
                    SELECT
                        `UAP_Project`
                    FROM
                        `User_Allowed_Projects`
                    WHERE
                        `UAP_User_ID` = :userId
                ) AND `CB_Host_ID` IN(
                    SELECT
                        `UAP_Host_ID`
                    FROM
                        `User_Allowed_Projects`
                    WHERE
                        `UAP_User_ID` = :userId
                )";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
