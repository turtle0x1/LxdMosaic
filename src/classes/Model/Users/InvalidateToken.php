<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class InvalidateToken
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function invalidate(int $userId)
    {
        $sql = "UPDATE
                    `User_Api_Tokens`
                SET
                    `UAT_Last_Used` = NOW()
		WHERE
                    `UAT_Last_Used` IS NULL
                AND
                    `UAT_User_ID` = :user_id";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":user_id"=>$userId
        ]);
        return $do->rowCount() ? true : false;
    }
}
