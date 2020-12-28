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
        $mod = isset($_ENV["DB_SQLITE"]) & !empty($_ENV["DB_SQLITE"]) ? "CASE WHEN `User_Ldap_ID` IS NOT NULL THEN 1 ELSE 0 END as `fromLdap`" : "IF(`User_Ldap_ID` IS NULL, 0, 1) as `fromLdap`";

        $sql = "SELECT
                    `User_ID` as `id`,
                    `User_Date_Created` as `created`,
                    `User_Name` as `username`,
                    `User_Admin` as `isAdmin`,
                    $mod
                FROM
                    `Users`
                ORDER BY
                    `User_ID` DESC
                ";
        $do = $this->database->query($sql);
        $do->execute();
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function fetchLdapIds()
    {
        $sql = "SELECT
                    `User_Ldap_ID`
                FROM
                    `Users`
                WHERE
                    `User_Ldap_ID` IS NOT NULL
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_COLUMN);
    }
}
