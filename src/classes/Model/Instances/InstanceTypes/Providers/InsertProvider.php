<?php

namespace dhope0000\LXDClient\Model\Instances\InstanceTypes\Providers;

use dhope0000\LXDClient\Model\Database\Database;

class InsertProvider
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(string $name) :bool
    {
        $sql = "INSERT INTO `Instace_Type_Providers`(`ITP_Name`) VALUES (:name)";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":name"=>$name
        ]);
        return $do->rowCount() ? true : false;
    }
}
