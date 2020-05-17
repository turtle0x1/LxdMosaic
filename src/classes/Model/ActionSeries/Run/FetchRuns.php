<?php

namespace dhope0000\LXDClient\Model\ActionSeries\Run;

use dhope0000\LXDClient\Model\Database\Database;

class FetchRuns
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchForSeries(int $actionSeries)
    {
        $sql = "SELECT
                    `ASR_ID` as `id`,
                    `ASR_Date_Created` as `started`,
                    `ASR_Date_Finished` as `finished`
                FROM
                    `Action_Series_Runs`
                WHERE
                    `ASR_AS_ID` = :actionSeries
                ORDER BY
                    `ASR_Date_Created` DESC
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":actionSeries"=>$actionSeries
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchRunDetails(int $runId)
    {
        $sql = "SELECT
                    `ASR_ID` as `id`,
                    `ASR_AS_ID` as `actionSeries`,
                    `ASR_Date_Created` as `started`,
                    `ASR_Date_Finished` as `finished`
                FROM
                    `Action_Series_Runs`
                WHERE
                    `ASR_ID` = :runId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":runId"=>$runId
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }
}
