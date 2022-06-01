<?php

namespace dhope0000\LXDClient\Model\Hosts\Settings\Alias;

use dhope0000\LXDClient\Model\Database\Database;

class HaveAlias
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function have(int $hostId, string $alias)
    {
        $sql = "SELECT
                    1
                FROM
                    `Hosts`
                WHERE
                    `Host_Alias` = :alias
                AND
                    `Host_ID` != :hostId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":alias"=>$alias,
            ":hostId"=>$hostId
        ]);
        return $do->fetch() ? true : false;
    }
}
