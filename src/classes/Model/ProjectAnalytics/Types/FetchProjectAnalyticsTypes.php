<?php

namespace dhope0000\LXDClient\Model\ProjectAnalytics\Types;

use dhope0000\LXDClient\Model\Database\Database;

class FetchProjectAnalyticsTypes
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchKnownKeysToIds()
    {
        $sql = "SELECT
                    `PAT_Key`,
                    `PAT_ID`
                FROM
                    `Project_Analytics_Types`
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}
