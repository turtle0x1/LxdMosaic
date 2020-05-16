<?php

namespace dhope0000\LXDClient\Model\ActionSeries;

use dhope0000\LXDClient\Model\Database\Database;

class InsertSeries
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(int $userId, string $name)
    {
        $sql = "INSERT INTO `Action_Series`
                (
                    `AS_User_ID`,
                    `AS_Name`
                ) VALUES (
                    :userId,
                    :name
                );
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":name"=>$name
        ]);
        return $do->rowCount() ? true : false;
    }
    public function getId() :int
    {
        return $this->database->lastInsertId();
    }
}
