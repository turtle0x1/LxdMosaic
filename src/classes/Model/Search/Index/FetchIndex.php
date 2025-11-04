<?php

namespace dhope0000\LXDClient\Model\Search\Index;

use dhope0000\LXDClient\Model\Database\Database;

class FetchIndex
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchLatestData()
    {
        $sql = "SELECT `SI_Data`
                FROM `Search_Index`
                ORDER BY `SI_Last_Updated` DESC
                ";
        return $this->database->query($sql)->fetchColumn();
    }
}
