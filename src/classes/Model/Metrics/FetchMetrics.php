<?php

namespace dhope0000\LXDClient\Model\Metrics;

use dhope0000\LXDClient\Model\Database\Database;

class FetchMetrics
{
    private \PDO $database;
    
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
                    `IMV_Instance_Name` as `instance`,
                    `IMT_Name` as `metric`
                FROM
                    `Instance_Metric_Values`
                LEFT JOIN `Instance_Metric_Types` ON `IMV_IMT_ID` = `IMT_ID`
                LEFT JOIN `Hosts` ON `IMV_Host_ID` = `Host_ID`
                ORDER BY `hostId` DESC";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_GROUP|\PDO::FETCH_ASSOC);
    }

    public function instanceHasMetrics(int $hostId, string $project, string $instance) :bool
    {
        $sql = "SELECT
                    1
                FROM
                    `Instance_Metric_Values`
                WHERE
                    `IMV_Host_ID` = :hostId
                AND
                    `IMV_Project_Name` = :project
                AND
                    `IMV_Instance_Name` = :instance
                LIMIT 1;
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":project"=>$project,
            ":instance"=>$instance
        ]);
        return $do->fetchColumn() ?  true : false;
    }

    /**
     * @TODO Bugged doesn't have a project type
     */
    public function fetchByHostContainerType(int $hostId, string $container, int $typeId, \DateTimeInterface $after)
    {
        $sql = "SELECT
                    `IMV_Date` as `date`,
                    `IMV_IMT_ID` as `typeId`,
                    `IMV_Data` as `data`
                FROM
                    `Instance_Metric_Values`
                WHERE
                    `IMV_Host_ID` = :hostId
                AND
                    `IMV_Instance_Name` = :container
                AND
                    `IMV_IMT_ID` = :typeId
                AND
                    `IMV_Date` > :after
                ORDER BY
                    `IMV_Date` DESC
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":container"=>$container,
            ":typeId"=>$typeId,
            ":after"=>$after->format("Y-m-d H:i")
        ]);
        return array_reverse($do->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function fetchAllTypes(int $hostId, string $container, string $project = "default")
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
                    `IMV_Instance_Name` = :container
                AND
                    `IMV_Project_Name` = :project
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":container"=>$container,
            ":project"=>$project
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchGroupedByFiveMinutes(\DateTimeInterface $olderThan)
    {
        $sql = "SELECT
                    `IMV_ID` as `id`,
                    `IMV_Host_ID` as `hostId`,
                    `IMV_Instance_Name` as `instance`,
                    `IMV_Project_Name` as `project`,
                    `IMV_IMT_ID` as `typeId`,
                    FROM_UNIXTIME(
                        FLOOR(UNIX_TIMESTAMP(`IMV_Date`) / 300) * 300
                    ) as `dTime`,
                    `IMV_Data` as `data`,
                    `IMV_Date` as `origDate`
                FROM
                    `Instance_Metric_Values`
                WHERE
                    `IMV_Date` < :olderThan
                ORDER BY
                    `IMV_Date` ASC
        ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":olderThan"=>$olderThan->format("Y-m-d H:i:s")
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
