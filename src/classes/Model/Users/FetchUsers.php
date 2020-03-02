<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class FetchUsers
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll()
    {
        $sql = "SELECT
                    `User_ID` as `id`,
                    `User_Date_Created` as `created`,
                    `User_Name` as `username`,
                    `User_Admin` as `isAdmin`
                FROM
                    `Users`
                ORDER BY
                    `User_ID` DESC
                ";
        $do = $this->database->query($sql);
        $do->execute();
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
