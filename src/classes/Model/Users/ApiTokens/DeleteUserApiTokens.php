<?php

namespace dhope0000\LXDClient\Model\Users\ApiTokens;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteUserApiTokens
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function deleteAllNonPermanent(int $userId) :bool
    {
        $sql = "DELETE FROM `User_Api_Tokens`
                WHERE
                    `UAT_User_ID` = :userId
                AND
                    `UAT_Permanent` = 0
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId
        ]);
        return $do->rowCount() ? true : false;
    }

    public function deleteAllPermanent(int $userId) :bool
    {
        $sql = "DELETE FROM `User_Api_Tokens`
                WHERE
                    `UAT_User_ID` = :userId
                AND
                    `UAT_Permanent` = 1
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId
        ]);
        return $do->rowCount() ? true : false;
    }
}
