<?php

namespace dhope0000\LXDClient\Model\Analytics;

use dhope0000\LXDClient\Model\Database\Database;

class FetchLatestData
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function lastHour()
    {
        $sql = "SELECT
                    `FA_Date_Created` as `dateTime`,
                    `FA_Total_Memory_Usage` as `memoryUsage`,
                    `FA_Active_Containers` as `activeContainers`,
                    `FA_Total_Storage_Usage` as `storageUsage`
                FROM
                    `Fleet_Analytics`
                WHERE
                    `FA_Date_Created` >= DATE_SUB(NOW(),INTERVAL 1 HOUR);
                ORDER BY
                    `FA_ID` ASC
                ";
        $do = $this->database->prepare($sql);
        $do->execute();
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function lastEntry()
    {
        $sql = "SELECT
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
                ";
        $do = $this->database->prepare($sql);
        $do->execute();
        return $do->fetch(\PDO::FETCH_ASSOC);
    }
}
