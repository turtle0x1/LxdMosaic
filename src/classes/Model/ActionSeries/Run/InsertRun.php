<?php

namespace dhope0000\LXDClient\Model\ActionSeries\Run;

use dhope0000\LXDClient\Model\Database\Database;

class InsertRun
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(int $userId, int $actionSeries)
    {
        $sql = "INSERT INTO `Action_Series_Runs`
                (
                    `ASR_User_ID`,
                    `ASR_AS_ID`
                ) VALUES (
                    :userId,
                    :actionSeries
                );
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":actionSeries"=>$actionSeries
        ]);
        return $do->rowCount() ? true : false;
    }

    public function getId() :int
    {
        return $this->database->lastInsertId();
    }
}
