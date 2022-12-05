<?php

namespace dhope0000\LXDClient\Model\Users\Dashboard\Graphs;

use dhope0000\LXDClient\Model\Database\Database;

class InsertDashboardGraph
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(
        int $dashboardId,
        string $name,
        int $hostId,
        string $instance,
        int $metricId,
        string $filter,
        string $range
    ) :bool {
        $sql = "INSERT INTO `User_Dashboard_Graphs`
                (
                    `UDG_UD_ID`,
                    `UDG_Name`,
                    `UDG_Host_ID`,
                    `UDG_Instance`,
                    `UDG_Metric_ID`,
                    `UDG_Filter`,
                    `UDG_Range`
                ) VALUES (
                    :dashboardId,
                    :name,
                    :hostId,
                    :instance,
                    :metricId,
                    :filter,
                    :range
                )
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":dashboardId"=>$dashboardId,
            ":name"=>$name,
            ":hostId"=>$hostId,
            ":instance"=>$instance,
            ":metricId"=>$metricId,
            ":filter"=>$filter,
            ":range"=>$range
        ]);
        return $do->rowCount() ? true : false;
    }
}
