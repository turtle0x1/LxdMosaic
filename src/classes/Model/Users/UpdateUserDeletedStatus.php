<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class UpdateUserDeletedStatus
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function setDeleted(int $userId, int $targetUserId)
    {
        $sql = "UPDATE
                    `Users`
                SET
                    `User_Deleted` = 1,
                    `User_Deleted_Date` = CURRENT_TIMESTAMP(),
                    `User_Deleted_By` = :userId
                WHERE
                    `User_ID` = :targetUserId";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":targetUserId"=>$targetUserId
        ]);
        return $do->rowCount() ? true : false;
    }
}
