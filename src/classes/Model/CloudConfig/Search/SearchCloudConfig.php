<?php

namespace dhope0000\LXDClient\Model\CloudConfig\Search;

use dhope0000\LXDClient\Model\Database\Database;

class SearchCloudConfig
{
    private \PDO $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function searchAll(string $criteria)
    {
        $sql = "SELECT
                    `CC_ID` as `id`,
                    `CC_Name` as `name`
                FROM
                    `Cloud_Config`
                WHERE
                    `CC_Name` LIKE :name
                ";

        $do = $this->database->prepare($sql);
        $do->execute([
            ":name"=>"%$criteria%"
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
