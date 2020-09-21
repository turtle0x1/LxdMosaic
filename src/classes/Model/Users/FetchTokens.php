<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class FetchTokens
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchLatestToken(int $userId)
    {
        $sql = "SELECT
                    `UAT_Token`
                FROM
                    `User_Api_Tokens`
                WHERE
                    `UAT_User_ID` = :userId
                ORDER BY
                    `UAT_ID` DESC
                LIMIT 1;
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId
        ]);
        return $do->fetchColumn();
    }

    public function fetchPermanentKeys(int $userId)
    {
        $sql = "SELECT
                    `UAT_ID` as `id`,
                    `UAT_Created_At` as `created`
                FROM
                    `User_Api_Tokens`
                WHERE
                    `UAT_Permanent` = 1
                AND
                    `UAT_User_ID` = :userId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
