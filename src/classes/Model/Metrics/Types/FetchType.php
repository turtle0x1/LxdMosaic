<?php

namespace dhope0000\LXDClient\Model\Metrics\Types;

use dhope0000\LXDClient\Model\Database\Database;

class FetchType
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchIdByTemplateKey(string $key)
    {
        $sql = "SELECT
                    `IMT_ID`
                FROM
                    `Instance_Metric_Types`
                WHERE
                    `IMT_Metrics_Template_Key` = :key
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":key"=>$key
        ]);
        return $do->fetchColumn();
    }


    public function formatTypeAsBytes(int $typeId) :bool
    {
        $sql = "SELECT
                    `IMT_Format_Bytes`
                FROM
                    `Instance_Metric_Types`
                WHERE
                    `IMT_ID` = :typeId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":typeId"=>$typeId
        ]);
        return (int) $do->fetchColumn() === 1;
    }
}
