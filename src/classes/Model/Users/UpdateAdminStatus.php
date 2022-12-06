<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class UpdateAdminStatus
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function update(int $userId, int $status) :bool
    {
        $sql = "UPDATE
                    `Users`
                SET
                    `User_Admin` = :status
                WHERE
                    `User_ID` = :userId";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":status"=>$status
        ]);
        return $do->rowCount() ? true : false;
    }
}
