<?php

namespace dhope0000\LXDClient\Model\Containers\InstanceTypes;

use dhope0000\LXDClient\Model\Database\Database;

class FetchInstanceTypes
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll()
    {
        $sql = "SELECT
                    `ITP_ID` as `providerId`,
                    `ITP_Name` as `providerName`,
                    `IT_Name` as `instanceName`,
                    `IT_CPU` as  `cpu`,
                    `IT_Mem` as `mem`
                FROM
                    `Instance_Types`
                LEFT JOIN `Instace_Type_Providers` ON
                    `Instace_Type_Providers`.`ITP_ID` = `Instance_Types`.`IT_Provider_ID`
                ORDER BY
                    `IT_ID`,
                    `ITP_ID`
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
