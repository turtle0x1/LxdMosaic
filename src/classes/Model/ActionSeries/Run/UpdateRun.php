<?php

namespace dhope0000\LXDClient\Model\ActionSeries\Run;

use dhope0000\LXDClient\Model\Database\Database;

class UpdateRun
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function closeRun(int $runId)
    {
        $sql = "UPDATE
                    `Action_Series_Runs`
                SET
                    `ASR_Date_Finished` = CURRENT_TIMESTAMP
                WHERE
                    `ASR_ID` = :runId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":runId"=>$runId
        ]);
        return $do->rowCount() ? true : false;
    }
}
