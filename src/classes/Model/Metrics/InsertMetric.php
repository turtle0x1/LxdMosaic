<?php

namespace dhope0000\LXDClient\Model\Metrics;

use dhope0000\LXDClient\Model\Database\Database;

class InsertMetric
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(string $date, int $hostId, string $containerName, int $typeId, string $data)
    {

        $sql = "INSERT INTO `Instance_Metric_Values`
                (
                    `IMV_Date`,
                    `IMV_Host_ID`,
                    `IMV_Instance_Name`,
                    `IMV_IMT_ID`,
                    `IMV_Data`
                ) VALUES (
                    :date,
                    :hostId,
                    :containerName,
                    :typeId,
                    :data
                );";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":date"=>$date,
            ":hostId"=>$hostId,
            ":containerName"=>$containerName,
            ":typeId"=>$typeId,
            ":data"=>$data
        ]);
        return $do->rowCount() ? true : false;
    }
}
