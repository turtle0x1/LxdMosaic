<?php

namespace dhope0000\LXDClient\Model\ActionSeries\Commands;

use dhope0000\LXDClient\Model\Database\Database;

class InsertSeriesCommand
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(
        int $userId,
        int $seriesId,
        string $command
    ) {
        $sql = "INSERT INTO `Action_Series_Commands`
                (
                    `ASC_User_ID`,
                    `ASC_AS_ID`,
                    `ASC_Command`,
                    `ASC_Term_On_Non_Zero`
                ) VALUES (
                    :userId,
                    :seriesId,
                    :command
                );
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":seriesId"=>$seriesId,
            ":command"=>$command
        ]);
        return $do->rowCount() ? true : false;
    }

    public function getId() :int
    {
        return $this->database->lastInsertId();
    }
}
