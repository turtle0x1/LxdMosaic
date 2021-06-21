<?php

namespace dhope0000\LXDClient\Model\Instances\InstanceTypes;

use dhope0000\LXDClient\Model\Database\Database;

class InsertInstanceType
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(int $providerId, string $name, float $cpu, float $mem)
    {
        $sql = "INSERT INTO `Instance_Types`(
                    `IT_Provider_ID`,
                    `IT_Name`,
                    `IT_CPU`,
                    `IT_Mem`
                ) VALUES (
                    :providerId,
                    :name,
                    :cpu,
                    :mem
                )";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":providerId"=>$providerId,
            ":name"=>$name,
            ":cpu"=>$cpu,
            ":mem"=>$mem
        ]);
        return $do->rowCount() ?  true : false;
    }
}
