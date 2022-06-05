<?php

namespace dhope0000\LXDClient\Model\Instances\InstanceTypes\Providers;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteProvider
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function delete(int $providerId)
    {
        $sql = "DELETE FROM `Instace_Type_Providers` WHERE `ITP_ID` = :providerId";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":providerId"=>$providerId
        ]);
        return $do->rowCount() ?  true : false;
    }
}
