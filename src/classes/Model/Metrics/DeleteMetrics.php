<?php

namespace dhope0000\LXDClient\Model\Metrics;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteMetrics
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function deleteForInstance(int $hostId, string $instance, string $project) :bool
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

    public function deleteForHost(int $hostId) :bool
    {
        $sql = "DELETE FROM
                    `Instance_Metric_Values`
                WHERE
                    `IMV_Host_ID` = :hostId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId
        ]);
        return $do->rowCount() ? true : false;
    }

    public function deleteByIds(array $ids) :bool
    {
        $qMarks = join(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM
                    `Instance_Metric_Values`
                WHERE
                    `IMV_ID` IN ($qMarks)
                ";
        $do = $this->database->prepare($sql);
        $do->execute($ids);
        return $do->rowCount() ? true : false;
    }

    public function deleteBefore(\DateTimeInterface $before) :bool
    {
        $sql = "DELETE FROM
                    `Instance_Metric_Values`
                WHERE
                    `IMV_Date` < :before
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":before"=>$before->format("Y-m-d H:i:s")
        ]);
        return $do->rowCount() ? true : false;
    }
}
