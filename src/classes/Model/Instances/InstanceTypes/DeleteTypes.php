<?php

namespace dhope0000\LXDClient\Model\Instances\InstanceTypes;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteTypes
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function deleteAllForProvider(int $providerId) :bool
    {
        $sql = "DELETE FROM `Instance_Types` WHERE `IT_Provider_ID` = :providerId";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":providerId"=>$providerId
        ]);
        return $do->rowCount() ? true : false;
    }
}
