<?php

namespace dhope0000\LXDClient\Model\ActionSeries\Run\Result;

use dhope0000\LXDClient\Model\Database\Database;

class InsertCommandResult
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(
        int $runId,
        int $commandId,
        string $dateExecuted,
        int $hostId,
        string $instance,
        string $outLog,
        string $errLog,
        int $return
    ) {
        $sql = "INSERT INTO `Action_Series_Command_Results`
                (
                    `ASCR_ASR_ID`,
                    `ASCR_ASC_ID`,
                    `ASCR_Date_Executed`,
                    `ASCR_Host_ID`,
                    `ASCR_Instance`,
                    `ASCR_Out_Log_Path`,
                    `ASCR_Err_Log_Path`,
                    `ASCR_Return`
                ) VALUES (
                    :runId,
                    :commandId,
                    :dateExecuted,
                    :hostId,
                    :instance,
                    :outLog,
                    :errLog,
                    :return
                );";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":runId"=>$runId,
            ":commandId" => $commandId,
            ":dateExecuted" => $dateExecuted,
            ":hostId" => $hostId,
            ":instance" => $instance,
            ":outLog" => $outLog,
            ":errLog" => $errLog,
            ":return" => $return
        ]);
        return $do->rowCount() ? true : false;
    }

    public function getId() :int
    {
        return $this->database->lastInsertId();
    }
}
