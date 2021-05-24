<?php

namespace dhope0000\LXDClient\Model\Backups;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteBackup
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function delete(int $backupId)
    {
        $sql = "DELETE FROM
                    `Container_Backups`
                WHERE
                    `CB_ID` = :backupId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":backupId"=>$backupId
        ]);
        return $do->rowCount() ? true : false;
    }

    public function setDeleted(int $backupId)
    {
        $sql = "UPDATE
                    `Container_Backups`
                SET
                    `CB_Deleted` = CURRENT_TIMESTAMP
                WHERE
                    `CB_ID` = :backupId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":backupId"=>$backupId
        ]);
        return $do->rowCount() ? true : false;
    }
}
