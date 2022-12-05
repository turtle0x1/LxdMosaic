<?php

namespace dhope0000\LXDClient\Model\Hosts\Backups\Strategies;

use dhope0000\LXDClient\Model\Database\Database;

class FetchStrategies
{
    private \PDO $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll()
    {
        $sql = "SELECT
                    `BS_ID` as `id`,
                    `BS_Name` as `name`
                FROM
                    `Backup_Strategies`
                ORDER BY
                    `BS_ID` ASC
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
