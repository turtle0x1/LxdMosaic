<?php

namespace dhope0000\LXDClient\Model\Users\AllowedProjects;

use dhope0000\LXDClient\Model\Database\Database;

class FetchAllowedProjects
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll(int $userId)
    {
        $sql = "SELECT
                    `UAP_Project`, -- Dont change order of columns or you'll
                                   -- break everything
                    `UAP_Host_ID`
                FROM
                    `User_Allowed_Projects`
                WHERE
                    `UAP_User_ID` = :userId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId
        ]);
        return $do->fetchAll(\PDO::FETCH_GROUP|\PDO::FETCH_COLUMN, 1);
    }
    public function fetchForHost(int $userId, int $hostId)
    {
        $sql = "SELECT
                    `UAP_Project`
                FROM
                    `User_Allowed_Projects`
                WHERE
                    `UAP_User_ID` = :userId
                AND
                    `UAP_Host_ID` = :hostId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":hostId"=>$hostId
        ]);
        return $do->fetchAll(\PDO::FETCH_COLUMN, 0);
    }
}
