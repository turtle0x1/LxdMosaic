<?php

namespace dhope0000\LXDClient\Model\Analytics;

use dhope0000\LXDClient\Model\Database\Database;

class FetchLatestData
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function lastHour()
    {
        $mod = isset($_ENV['DB_SQLITE']) && !empty($_ENV['DB_SQLITE']) ? "datetime('now', '-3600 seconds')" : 'DATE_SUB(NOW(), INTERVAL 1 HOUR)';
        $sql = "SELECT
                    `FA_Date_Created` as `dateTime`,
                    `FA_Total_Memory_Usage` as `memoryUsage`,
                    `FA_Active_Containers` as `activeContainers`,
                    `FA_Total_Storage_Usage` as `storageUsage`
                FROM
                    `Fleet_Analytics`
                WHERE
                    `FA_Date_Created` >= {$mod}
                ORDER BY
                    `FA_ID` ASC
                ";
        $do = $this->database->prepare($sql);
        $do->execute();
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function lastEntry()
    {
        $sql = 'SELECT
                    `FA_Date_Created` as `dateTime`,
                    `FA_Total_Memory_Usage` as `memoryUsage`,
                    `FA_Active_Containers` as `activeContainers`,
                    `FA_Total_Storage_Usage` as `storageUsage`,
                    `FA_Total_Storage_Available` as `storageAvailable`
                FROM
                    `Fleet_Analytics`
                ORDER BY
                    `FA_ID` DESC
                LIMIT 1
                ';
        $do = $this->database->prepare($sql);
        $do->execute();
        return $do->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchAll()
    {
        $sql = 'SELECT
                    `FA_Date_Created` as `dateTime`,
                    `FA_Total_Memory_Usage` as `memoryUsage`,
                    `FA_Active_Containers` as `activeContainers`,
                    `FA_Total_Storage_Usage` as `storageUsage`,
                    `FA_Total_Storage_Available` as `storageAvailable`
                FROM
                    `Fleet_Analytics`
                ORDER BY
                    `FA_ID` ASC
                ';
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
