<?php

namespace dhope0000\LXDClient\Model\Backups;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteBackup
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function deleteBefore(\DateTimeInterface $before)
    {
        $sql = 'DELETE FROM
                    `Container_Backups`
                WHERE
                    `CB_Deleted` < :before
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':before' => $before->format('Y-m-d H:i:s'),
        ]);
        return $do->rowCount() ? true : false;
    }

    public function setDeleted(int $backupId)
    {
        $sql = 'UPDATE
                    `Container_Backups`
                SET
                    `CB_Deleted` = CURRENT_TIMESTAMP
                WHERE
                    `CB_ID` = :backupId
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':backupId' => $backupId,
        ]);
        return $do->rowCount() ? true : false;
    }

    public function deleteForHost(int $hostId)
    {
        $sql = 'DELETE FROM
                    `Container_Backups`
                WHERE
                    `CB_Host_ID` = :hostId
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':hostId' => $hostId,
        ]);
        return $do->rowCount() ? true : false;
    }
}
