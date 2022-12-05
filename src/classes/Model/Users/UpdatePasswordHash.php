<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class UpdatePasswordHash
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function update(int $userId, string $passwordHash) :bool
    {
        $sql = "UPDATE
                    `Users`
                SET
                    `User_Password` = :password
                WHERE
                    `User_ID` = :userId";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":password"=>$passwordHash
        ]);
        return $do->rowCount() ? true : false;
    }
}
