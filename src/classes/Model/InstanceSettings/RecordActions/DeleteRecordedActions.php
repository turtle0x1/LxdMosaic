<?php

namespace dhope0000\LXDClient\Model\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteRecordedActions
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function deleteForUser(int $userId) :bool
    {
        $sql = "DELETE FROM `Recorded_Actions`
                WHERE
                    `RA_User_ID` = :userId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId
        ]);
        return $do->rowCount() ? true : false;
    }
}
