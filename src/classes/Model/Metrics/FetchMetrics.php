<?php

namespace dhope0000\LXDClient\Model\Metrics;

use dhope0000\LXDClient\Model\Database\Database;

class FetchMetrics
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
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
                    `IMV_Date` ASC
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":container"=>$container,
            ":typeId"=>$typeId
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
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
