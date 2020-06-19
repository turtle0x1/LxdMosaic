<?php

namespace dhope0000\LXDClient\Model\Users\Dashboard\Graphs;

use dhope0000\LXDClient\Model\Database\Database;

class FetchDashboardGraphs
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll(int $dashboardId)
    {
        $sql = "SELECT
                    `UDG_Name` as `graphName`,
                    `UDG_ID` as `graphId`,
                    `UDG_Host_ID` as `hostId`,
                    `UDG_Instance` as `instance`,
                    `UDG_Metric_ID` as `metricId`,
                    `UDG_Filter` as `filter`,
                    `UDG_Range` as `range`
                FROM
                    `User_Dashboard_Graphs`
                WHERE
                    `UDG_UD_ID` = :dashboardId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":dashboardId"=>$dashboardId
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
