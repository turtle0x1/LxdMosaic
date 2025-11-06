<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Instances;

use dhope0000\LXDClient\Model\Database\Database;

class FetchInstanceBackups
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll(int $hostId, string $instance)
    {
        $sql = 'SELECT
                    `CB_ID` as `id`,
                    `CB_Backup_Date_Created` as `dateCreated`,
                    `CB_Backup` as `backupName`,
                    `CB_Local_Path` as `localFilePath`,
                    `CB_Filesize` as `fileszie`,
                    `CB_Deleted` as `dateDeleted`,
                    `CB_Failed` as `failed`,
                    `CB_Failed_Reason` as `failedReason`
                FROM
                    `Container_Backups`
                WHERE
                    `CB_Container` = :container
                AND
                    `CB_Host_ID` = :hostId
                ORDER BY
                    `dateCreated` DESC
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':hostId' => $hostId,
            ':container' => $instance,
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
