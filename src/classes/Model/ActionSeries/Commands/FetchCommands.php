<?php

namespace dhope0000\LXDClient\Model\ActionSeries\Commands;

use dhope0000\LXDClient\Model\Database\Database;

class FetchCommands
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchForSeries(int $seriesId)
    {
        $sql = "SELECT
                    `ASC_ID` as `id`,
                    `ASC_Name` as `name`,
                    `ASC_Command` as `command`,
                    `ASC_ASC_Parent` as `parentId`,
                    `ASC_ASC_Parent_Return_Action` as `parentReturnAction`
                FROM
                    `Action_Series_Commands`
                WHERE
                    `ASC_AS_ID` = :seriesId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":seriesId"=>$seriesId
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
