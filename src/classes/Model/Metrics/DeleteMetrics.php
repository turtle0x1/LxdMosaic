<?php

namespace dhope0000\LXDClient\Model\Metrics;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteMetrics
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function deleteForInstance(int $hostId, string $instance, string $project)
    {
        $sql = "DELETE FROM
                    `Instance_Metric_Values`
                WHERE
                    `IMV_Host_ID` = :hostId
                AND
                    `IMV_Instance_Name` = :instance
                AND
                    `IMV_Project_Name` = :project
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":instance"=>$instance,
            ":project"=>$project
        ]);
        return $do->rowCount() ? true : false;
    }
}
