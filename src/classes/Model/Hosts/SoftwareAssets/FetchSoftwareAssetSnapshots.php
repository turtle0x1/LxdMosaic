<?php

namespace dhope0000\LXDClient\Model\Hosts\SoftwareAssets;

use dhope0000\LXDClient\Model\Database\Database;

class FetchSoftwareAssetSnapshots
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchLatest()
    {
        $sql = "SELECT SAS_Date as `date`, SAS_Data as `data` FROM `Software_Assets_Snapshots` WHERE SAS_ID = (SELECT
                    SAS_ID
                FROM
                    `Software_Assets_Snapshots`
                ORDER BY
                    `SAS_Date` DESC
                LIMIT 1)
                ";
        return $this->database->query($sql)->fetch(\PDO::FETCH_ASSOC);
    }
    public function fetchForDate(\DateTimeImmutable $date)
    {
        $sql = "SELECT
                    JSON_UNQUOTE(`SAS_Data`) as `data`
                FROM
                    `Software_Assets_Snapshots`
                WHERE
                    `SAS_Date` = :date
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
                    `SAS_ID` as `id`,
                    `SAS_Date` as `date`,
                    `SAS_Last_Updated` as `lastUpdate`
                FROM
                    `Software_Assets_Snapshots`
                ORDER BY
                    `SAS_Date` DESC
                LIMIT 7
                ";
        return $this->database->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}
