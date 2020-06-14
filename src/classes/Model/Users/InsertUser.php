<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class InsertUser
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(string $username, string $passwordHash)
    {
        $sql = "INSERT INTO `Users`
                (
                    `User_Name`,
                    `User_Password`
                ) VALUES (
                    :username,
                    :password
                );
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":username"=>$username,
            ":password"=>$passwordHash
        ]);
        return $do->rowCount() ? true : false;
    }
}
