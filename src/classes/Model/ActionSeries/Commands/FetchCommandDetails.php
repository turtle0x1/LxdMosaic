<?php

namespace dhope0000\LXDClient\Model\ActionSeries\Commands;

use dhope0000\LXDClient\Model\Database\Database;

class FetchCommandDetails
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchDetails(int $commandId)
    {
        $sql = "SELECT
                    `ASC_Command` as `command`
                FROM
                    `Action_Series_Commands`
                WHERE
                    `ASC_ID` = :commandId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":commandId"=>$commandId
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }
}
