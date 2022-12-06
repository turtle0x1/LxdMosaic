<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Instances;

use dhope0000\LXDClient\Model\Database\Database;

class InsertInstanceBackup
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(
        \DateTime $backupDateCreated,
        int $hostId,
        string $project,
        string $instance,
        string $backup,
        string $localPath,
        int $filesize,
        int $failed = 0,
        string $failedReason = ""
    ) :bool {
        $sql = "INSERT INTO `Container_Backups`
                (
                    `CB_Backup_Date_Created`,
                    `CB_Host_ID`,
                    `CB_Project`,
                    `CB_Container`,
                    `CB_Backup`,
                    `CB_Local_Path`,
                    `CB_Filesize`,
                    `CB_Failed`,
                    `CB_Failed_Reason`
                ) VALUES (
                    :backupDateCreated,
                    :hostId,
                    :project,
                    :instance,
                    :backup,
                    :localPath,
                    :filesize,
                    :failed,
                    :failedReason
                );";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":backupDateCreated"=>$backupDateCreated->format("Y-m-d H:i:s"),
            ":hostId"=>$hostId,
            ":project"=>$project,
            ":instance"=>$instance,
            ":backup"=>$backup,
            ":localPath"=>$localPath,
            ":filesize"=>$filesize,
            ":failed"=>$failed,
            ":failedReason"=>$failedReason
        ]);
        return $this->database->lastInsertId();
    }
}
