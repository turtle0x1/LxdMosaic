<?php

namespace dhope0000\LXDClient\Model\Hosts\Alias;

use dhope0000\LXDClient\Model\Database\Database;

class HaveAlias
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function have(string $alias)
    {
        $sql = "SELECT
                    1
                FROM
                    `Hosts`
                WHERE
                    `Host_Alias` = :alias
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":alias"=>$alias,
        ]);
        return $do->fetch() ? true : false;
    }
}
