<?php

namespace dhope0000\LXDClient\Model\Hosts\SoftwareAssets;

use dhope0000\LXDClient\Model\Database\Database;

class InsertSoftwareAssetsSnapshot
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(\DateTimeImmutable $date, array $data)
    {
        $sql = 'INSERT INTO `Software_Assets_Snapshots`
                (
                    `SAS_Last_Updated`,
                    `SAS_Date`,
                    `SAS_Data`
                ) VALUES (
                    CURRENT_TIMESTAMP(),
                    :date,
                    :data
                ) ON DUPLICATE KEY UPDATE
                    `SAS_Last_Updated` = CURRENT_TIMESTAMP(),
                    `SAS_Data` = :data;
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':date' => $date->format('Y-m-d'),
            ':data' => json_encode($data),
        ]);
        return $do->rowCount() ? true : false;
    }
}
