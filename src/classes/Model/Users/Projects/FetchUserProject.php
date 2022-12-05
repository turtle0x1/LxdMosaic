<?php

namespace dhope0000\LXDClient\Model\Users\Projects;

use dhope0000\LXDClient\Model\Database\Database;

class FetchUserProject
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetch(int $userId, int $hostId) :string
    {
        $sql = "SELECT
                    `UHP_Project`
                FROM
                    `User_Host_Projects`
                WHERE
                    `UHP_User_ID` = :userId
                AND
                    `UHP_Host_ID` = :hostId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":hostId"=>$hostId
        ]);
        return $do->fetchColumn();
    }

    public function fetchCurrentProjects(int $userId) :array
    {
        $sql = "SELECT
                    `UHP_Host_ID`,
                    `UHP_Project`
                FROM
                    `User_Host_Projects`
                WHERE
                    `UHP_User_ID` = :userId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId
        ]);
        return $do->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}
