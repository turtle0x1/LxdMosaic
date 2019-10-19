<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Containers;

use dhope0000\LXDClient\Model\Database\Database;

class FetchContainerBackups
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll(int $hostId, string $container)
    {
        $sql = "SELECT
                    `CB_Backup_Date_Created` as `dateCreated`,
                    `CB_Backup` as `backupName`,
                    `CB_Local_Path` as `localFilePath`
                FROM
                    `Container_Backups`
                WHERE
                    `CB_Container` = :container
                AND
                    `CB_Host_ID` = :hostId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":container"=>$container
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
