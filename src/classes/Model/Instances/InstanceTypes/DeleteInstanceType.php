<?php

namespace dhope0000\LXDClient\Model\Instances\InstanceTypes;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteInstanceType
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function delete(int $typeId)
    {
        $sql = "DELETE FROM `Instance_Types` WHERE `IT_ID` = :typeId";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":typeId"=>$typeId
        ]);
        return $do->rowCount() ?  true : false;
    }
}
