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

    public function fetchAllUsersPermissions()
    {
        $sql = "SELECT
                    `UAP_Host_ID` as `hostId`,
                    `UAP_Project` as `project`,
                    `UAP_User_ID` as `userId`,
                    `User_Name` as `userName`
                FROM
                    `User_Allowed_Projects`
                LEFT JOIN `Users` ON
                    `User_ID` = `UAP_User_ID`
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
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

    public function fetchForHostProject(int $hostId, string $project)
    {
        $sql = "SELECT
                    `UAP_ID` as `id`,
                    `UAP_User_ID` as `userId`,
                    `Users`.`User_Name` as `userName`
                FROM
                    `User_Allowed_Projects`
                LEFT JOIN `Users` ON
                	`Users`.`User_ID` = `UAP_User_ID`
                WHERE
                    `UAP_Host_ID` = :hostId
                AND
                    `UAP_Project` = :project
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":project"=>$project
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchUsersCanAcessProject(int $hostId, string $project)
    {
        $sql = "SELECT
                    `Users`.`User_Name`
                FROM
                    `User_Allowed_Projects`
                LEFT JOIN `Users` ON
                    `Users`.`User_ID` = `User_Allowed_Projects`.`UAP_User_ID`
                WHERE
                    `UAP_Host_ID` = :hostId
                AND
                    `UAP_Project` = :project
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":project"=>$project
        ]);
        return $do->fetchAll(\PDO::FETCH_COLUMN, 0);
    }
}
