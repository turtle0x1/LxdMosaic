<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Profiles;

use dhope0000\LXDClient\Model\Database\Database;

class InsertProfilesBackup
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(
        int $hostId,
        string $project,
        string $localPath,
        int $filesize
    ) {
        $sql = "INSERT INTO `Profile_Backups`(
                    `PB_Host_ID`,
                    `PB_Local_Path`,
                    `PB_Project`,
                    `PB_Filesize`
                ) VALUES(
                    :hostId,
                    :localPath,
                    :project,
                    :filesize
                )";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":localPath"=>$localPath,
            ":project"=>$project,
            ":filesize"=>$filesize
        ]);
        return $this->database->lastInsertId();
    }
}
