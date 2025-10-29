<?php

namespace dhope0000\LXDClient\Model\Hosts\Timers;

use dhope0000\LXDClient\Model\Database\Database;

class FetchTimersSnapshot
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchForDate(\DateTimeImmutable $date)
    {
        $sql = "SELECT
                    JSON_UNQUOTE(`TS_Data`) as `data`
                FROM
                    `Timers_Snapshots`
                WHERE
                    `TS_Date` = :date
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":date" => $date->format("Y-m-d")
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchLastSevenHeaders()
    {
        $sql = "SELECT
                    `TS_ID` as `id`,
                    `TS_Date` as `date`,
                    `TS_Last_Updated` as `lastUpdate`
                FROM
                    `Timers_Snapshots`
                ORDER BY
                    `TS_Date` DESC
                LIMIT 7
                ";
        return $this->database->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}
