<?php

namespace dhope0000\LXDClient\Model\ProjectAnalytics;

use dhope0000\LXDClient\Model\Database\Database;

class FetchAnalytics
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchBetween(
        \DateTimeInterface $start,
        \DateTimeInterface $end
    ) {
        $sql = "SELECT
                    `PA_Date_Created` as `created`,
                    `PA_Host_ID` as `hostId`,
                    `PA_Project` as `project`,
                    `PA_Value` as `value`,
                    `PA_Limit` as `limit`,
                    `Project_Analytics_Types`.`PAT_Name` as `typeName`,
                    COALESCE(`Host_Alias`, `Host_Url_And_Port`) as `hostAlias`
                FROM
                    `Project_Analytics`
                LEFT JOIN `Project_Analytics_Types` ON
                    `PAT_ID` = `PA_Type_ID`
                LEFT JOIN `Hosts`ON
                    `Host_ID` = `PA_Host_ID`
                WHERE
                    `PA_Date_Created` BETWEEN :startDate AND :endDate
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":startDate"=>$start->format("Y-m-d H:i:s"),
            ":endDate"=>$end->format("Y-m-d H:i:s")
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
