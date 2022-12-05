<?php

namespace dhope0000\LXDClient\Model\Instances\InstanceTypes;

use dhope0000\LXDClient\Model\Database\Database;

class FetchInstanceTypes
{
    private \PDO $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll()
    {
        $sql = "SELECT
                    `ITP_ID` as `providerId`,
                    `IT_ID` as `id`,
                    `ITP_Name` as `providerName`,
                    `IT_Name` as `instanceName`,
                    `IT_CPU` as  `cpu`,
                    `IT_Mem` as `mem`
                FROM
                    `Instace_Type_Providers`
                LEFT JOIN `Instance_Types` ON
                    `Instace_Type_Providers`.`ITP_ID` = `Instance_Types`.`IT_Provider_ID`
                ORDER BY
                    `IT_ID`,
                    `ITP_ID`
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
