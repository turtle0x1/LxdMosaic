<?php

namespace dhope0000\LXDClient\Model\Hosts\Timers;

use dhope0000\LXDClient\Model\Database\Database;

class InsertTimersSnapshot
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(\DateTimeImmutable $date, array $data)
    {
        $sql = 'INSERT INTO `Timers_Snapshots`
                (
                    `TS_Last_Updated`,
                    `TS_Date`,
                    `TS_Data`
                ) VALUES (
                    CURRENT_TIMESTAMP(),
                    :date,
                    :data
                ) ON DUPLICATE KEY UPDATE
                    `TS_Last_Updated` = CURRENT_TIMESTAMP(),
                    `TS_Data` = :data;
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':date' => $date->format('Y-m-d'),
            ':data' => json_encode($data),
        ]);
        return $do->rowCount() ? true : false;
    }
}
