<?php

namespace dhope0000\LXDClient\Model\Metrics;

use dhope0000\LXDClient\Model\Database\Database;

class FetchMetrics
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAvailableMetricsByHost()
    {
        $sql = "SELECT DISTINCT
                	COALESCE(`Host_Alias`, `Host_Url_And_Port`) as `hostAlias`,
                    `IMV_Host_ID` as `hostId`,
                    `IMV_IMT_ID` as `metricId`,
                    `IMV_Containr_Name` as `instance`,
                    `IMT_Name` as `metric`
                FROM
                    `Instance_Metric_Values`
                LEFT JOIN `Instance_Metric_Types` ON `IMV_IMT_ID` = `IMT_ID`
                LEFT JOIN `Hosts` ON `IMV_Host_ID` = `Host_ID`
                ORDER BY `hostId` DESC";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_GROUP|\PDO::FETCH_ASSOC);
    }

    public function fetchByHostContainerType(int $hostId, string $container, int $typeId)
    {
        $sql = "SELECT
                    DATE_FORMAT(`IMV_Date`, '%H:%i:%s') as `date`,
                    `IMV_IMT_ID` as `typeId`,
                    `IMV_Data` as `data`
                FROM
                    `Instance_Metric_Values`
                WHERE
                    `IMV_Host_ID` = :hostId
                AND
                    `IMV_Containr_Name` = :container
                AND
                    `IMV_IMT_ID` = :typeId
                ORDER BY
                    `IMV_Date` DESC
                LIMIT  15
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":container"=>$container,
            ":typeId"=>$typeId
        ]);
        return array_reverse($do->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function fetchAllTypes(int $hostId, string $container)
    {
        $sql = "SELECT DISTINCT
                    `IMV_IMT_ID` as `typeId`,
                    `IMT_Name` as `type`
                FROM
                    `Instance_Metric_Values`
                LEFT JOIN `Instance_Metric_Types` ON
                    `Instance_Metric_Types`.`IMT_ID` = `Instance_Metric_Values`.`IMV_IMT_ID`
                WHERE
                    `IMV_Host_ID` = :hostId
                AND
                    `IMV_Containr_Name` = :container
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":container"=>$container
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
