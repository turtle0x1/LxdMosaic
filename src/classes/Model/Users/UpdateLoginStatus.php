<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class UpdateLoginStatus
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function update(int $userId, int $status = null)
    {
        $sql = "UPDATE
                    `Users`
                SET
                    `User_Login_Disabled` = :status
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
