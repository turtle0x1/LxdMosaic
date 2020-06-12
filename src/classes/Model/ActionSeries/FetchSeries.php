<?php

namespace dhope0000\LXDClient\Model\ActionSeries;

use dhope0000\LXDClient\Model\Database\Database;

class FetchSeries
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll()
    {
        $sql = "SELECT
                    `AS_ID` as `id`,
                    `AS_Name` as `name`
                FROM
                    `Action_Series`
                ORDER BY
                    `AS_ID` DESC
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchDetails(int $actionSeries)
    {
        $sql = "SELECT
                    `AS_ID` as `id`,
                    `AS_Name` as `name`
                FROM
                    `Action_Series`
                WHERE
                    `AS_ID` = :actionSeries
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":actionSeries"=>$actionSeries
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }
}
