<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class InsertToken
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(int $userId, string $token, int $permanent = 0) :bool
    {
        $sql = "INSERT INTO `User_Api_Tokens`
                (
                    `UAT_Token`,
                    `UAT_User_ID`,
                    `UAT_Permanent`
                ) VALUES (
                    :token,
                    :user_id,
                    :permanent
                );
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":token"=>$token,
            ":user_id"=>$userId,
            ":permanent"=>$permanent
        ]);
        return $do->rowCount() ? true : false;
    }
}
