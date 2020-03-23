<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class InsertToken
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(string $userId, string $token)
    {
        $sql = "INSERT INTO `User_Api_Tokens`
                (
                    `UAT_Token`,
                    `UAT_User_ID`
                ) VALUES (
                    :token,
                    :user_id
                );
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":token"=>$token,
            ":user_id"=>$userId
        ]);
        return $do->rowCount() ? true : false;
    }
}
