<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class FetchUserDetails
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchHash(string $username)
    {
        $sql = "SELECT
                    `User_Password`
                FROM
                    `Users`
                WHERE
                    `User_Name` = :username
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":username"=>$username
        ]);
        return $do->fetchColumn();
    }

    public function fetchId(string $username)
    {
        $sql = "SELECT
                    `User_ID`
                FROM
                    `Users`
                WHERE
                    `User_Name` = :username
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":username"=>$username
        ]);
        return $do->fetchColumn();
    }
}
