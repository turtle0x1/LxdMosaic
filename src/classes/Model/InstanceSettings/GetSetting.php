<?php

namespace dhope0000\LXDClient\Model\InstanceSettings;

use dhope0000\LXDClient\Model\Database\Database;

class GetSetting
{
    private \PDO $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function getSettingLatestValue(int $settingId)
    {
        $sql = "SELECT
                    `ISV_Value`
                FROM
                    `Instance_Settings_Values`
                WHERE
                    `ISV_IS_ID` = :settingId
                ORDER BY
                    `ISV_ID` DESC
                LIMIT 1
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":settingId"=>$settingId
        ]);
        return $do->fetchColumn();
    }
}
