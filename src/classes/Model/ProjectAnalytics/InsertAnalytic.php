<?php

namespace dhope0000\LXDClient\Model\ProjectAnalytics;

use dhope0000\LXDClient\Model\Database\Database;

class InsertAnalytic
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(
        \DateTimeInterface $date,
        int $hostId,
        string $project,
        int $typeId,
        int $value,
        ?int $limit
    ) :bool {
        $sql = "INSERT INTO `Project_Analytics` (
                    `PA_Date_Created`,
                    `PA_Host_ID`,
                    `PA_Project`,
                    `PA_Type_ID`,
                    `PA_Value`,
                    `PA_Limit`
                ) VALUES (
                    :cDate,
                    :hostId,
                    :project,
                    :typeId,
                    :value,
                    :pLimit
                )
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":cDate"=>$date->format("Y-m-d H:i:s"),
            ":hostId"=>$hostId,
            ":project"=>$project,
            ":typeId"=>$typeId,
            ":value"=>$value,
            ":pLimit"=>$limit
        ]);
        return $do->rowCount() ? true : false;
    }
}
