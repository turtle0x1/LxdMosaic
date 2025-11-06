<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class InvalidateToken
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function invalidate(int $userId, string $token)
    {
        $sql = 'DELETE FROM
                    `User_Api_Tokens`
                WHERE
                    `UAT_Token` = :token
                AND
                    `UAT_User_ID` = :user_id
		AND
                    `UAT_Permanent` = 0'
        ;
        $do = $this->database->prepare($sql);
        $do->execute([
            ':user_id' => $userId,
            ':token' => $token,
        ]);
        return $do->rowCount() ? true : false;
    }
}
