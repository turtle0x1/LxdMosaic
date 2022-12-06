<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class UpdateUsername
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function update(int $targetUserId, string $name) :bool
    {
        $sql = "UPDATE
                    `Users`
                SET
                    `User_Name` = :name
                WHERE
                    `User_ID` = :targetUserId";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":targetUserId"=>$targetUserId,
            ":name"=>$name
        ]);
        return $do->rowCount() ? true : false;
    }
}
