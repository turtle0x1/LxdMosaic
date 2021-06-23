<?php

namespace dhope0000\LXDClient\Model\Instances\InstanceTypes;

use dhope0000\LXDClient\Model\Database\Database;

class FetchInstanceType
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchByName($name)
    {
        $sql = "SELECT
                    `ITP_ID` as `providerId`,
                    `IT_ID` as `id`,
                    `ITP_Name` as `providerName`,
                    `IT_Name` as `instanceName`,
                    `IT_CPU` as  `cpu`,
                    `IT_Mem` as `mem`
                FROM
                    `Instance_Types`
                LEFT JOIN `Instace_Type_Providers` ON
                    `Instace_Type_Providers`.`ITP_ID` = `Instance_Types`.`IT_Provider_ID`
                WHERE
                    `IT_Name` = :name
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":name"=>$name
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }
}
