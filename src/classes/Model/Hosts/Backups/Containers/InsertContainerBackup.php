<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Containers;

use dhope0000\LXDClient\Model\Database\Database;

class InsertContainerBackup
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(
        \DateTime $backupDateCreated,
        int $hostId,
        string $container,
        string $backup,
        string $localPath
    ) {
        $sql = "INSERT INTO `Container_Backups`
                (
                    `CB_Backup_Date_Created`,
                    `CB_Host_ID`,
                    `CB_Container`,
                    `CB_Backup`,
                    `CB_Local_Path`
                ) VALUES (
                    :backupDateCreated,
                    :hostId,
                    :container,
                    :backup,
                    :localPath
                );";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":backupDateCreated"=>$backupDateCreated->format("Y-m-d H:i:s"),
            ":hostId"=>$hostId,
            ":container"=>$container,
            ":backup"=>$backup,
            ":localPath"=>$localPath
        ]);
        return $do->rowCount() ? true : false;
    }
}
