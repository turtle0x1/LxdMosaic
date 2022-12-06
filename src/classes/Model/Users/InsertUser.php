<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class InsertUser
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(string $username, string $passwordHash, string $ldapId = null) :bool
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

    public function getId() :int
    {
        $newUserId = $this->database->lastInsertId();
        if ($newUserId === false) {
            throw new \Exception("User wasn't created successfully", 1);
        }
        return (int) $newUserId;
    }
}
