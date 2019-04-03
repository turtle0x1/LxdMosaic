<?php

namespace dhope0000\LXDClient\Model\Hosts\Alias;

use dhope0000\LXDClient\Model\Database\Database;

class SetAlias
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function set(int $hostId, string $alias)
    {
        $sql = "UPDATE `Hosts`
                SET
                    `Host_Alias` = :alias
                WHERE
                    `Host_ID` = :hostId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":alias"=>$alias,
            ":hostId"=>$hostId
        ]);
        return $do->rowCount() ? true : false;
    }
}
