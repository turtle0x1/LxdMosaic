<?php

namespace dhope0000\LXDClient\Model\Analytics;

use dhope0000\LXDClient\Model\Database\Database;

class StoreFleetAnalytics
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function store(int $totalMemory, int $activeContainers)
    {
        $sql = "INSERT INTO `Fleet_Analytics` (
                    `FA_Total_Memory_Usage`,
                    `FA_Active_Containers`
                ) VALUES (
                    :memoryUsage,
                    :activeContainers
                )";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":memoryUsage"=>$totalMemory,
            ":activeContainers"=>$activeContainers
        ]);
        return $do->rowCount() ? true : false;
    }
}
