<?php

namespace dhope0000\LXDClient\Model\Backups\Profiles;

use dhope0000\LXDClient\Model\Database\Database;

class FetchProfileBackups
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll()
    {
        $sql = "SELECT
                    `PB_ID` as `id`,
                    `PB_Date_Created` as `storedDateCreated`,
                    `PB_Host_ID` as `hostId`,
                    `PB_Local_Path` as `localPath`,
                    `PB_Project` as `project`,
                    `PB_Deleted` as `deletedDate`,
                    `PB_Filesize` as `filesize`
                FROM
                    `Profile_Backups`
                ORDER BY
                    `storedDateCreated` DESC
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchBackupsUserCanAccess(int $userId)
    {
        $sql = "SELECT
                    `PB_ID` as `id`,
                    `PB_Date_Created` as `storedDateCreated`,
                    `PB_Host_ID` as `hostId`,
                    `PB_Local_Path` as `localPath`,
                    `PB_Project` as `project`,
                    `PB_Deleted` as `deletedDate`,
                    `PB_Filesize` as `filesize`
                FROM
                    `Profile_Backups`
                WHERE
                    `PB_Project` IN(
                    SELECT
                        `UAP_Project`
                    FROM
                        `User_Allowed_Projects`
                    WHERE
                        `UAP_User_ID` = :userId
                ) AND `PB_Host_ID` IN(
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
