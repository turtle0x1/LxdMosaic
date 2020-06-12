<?php

namespace dhope0000\LXDClient\Model\ActionSeries\Run\Result;

use dhope0000\LXDClient\Model\Database\Database;

class FetchCommandResults
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchByRunGroupedByHost(int $runId)
    {
        $sql = "SELECT
                    `ASCR_Host_ID` as `hostId`,
                    `ASCR_Instance` as `instance`,
                    `ASCR_ASC_ID` as `commandId`,
                    `ASCR_Out_Log_Path` as `outLog`,
                    `ASCR_Err_Log_Path` as `errLog`,
                    `ASCR_Return` as `return`
                FROM
                    `Action_Series_Command_Results`
                WHERE
                    `ASCR_ASR_ID` = :runId
                ORDER BY
                    `ASCR_Date_Executed` ASC
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":runId"=>$runId
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC|\PDO::FETCH_GROUP);
    }
}
