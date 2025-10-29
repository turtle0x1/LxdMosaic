<?php

namespace dhope0000\LXDClient\Model\InstanceSettings\ImageServers;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteImageServer
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function delete(string $alias)
    {
        $sql = "DELETE FROM `Image_Servers` WHERE IS_Alias = :alias";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":alias" => $alias
        ]);
        return $do->rowCount() ? true : false;
    }
}
