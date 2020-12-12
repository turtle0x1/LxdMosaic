<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class InsertUser
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(string $username, string $passwordHash, $ldapId = null)
    {
        $sql = "INSERT INTO `Users`
                (
                    `User_Name`,
                    `User_Password`,
                    `User_Ldap_ID`
                ) VALUES (
                    :username,
                    :password,
                    :ldapId
                );
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":username"=>$username,
            ":password"=>$passwordHash,
            ":ldapId"=>$ldapId
        ]);
        return $do->rowCount() ? true : false;
    }

    public function getId()
    {
        return $this->database->lastInsertId();
    }
}
