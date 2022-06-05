<?php

namespace dhope0000\LXDClient\Model\InstanceSettings;

use dhope0000\LXDClient\Model\Database\Database;

class InsertSetting
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(int $settingId, $value)
    {
        $sql = "INSERT INTO
                    `Instance_Settings_Values`
                (
                    `ISV_IS_ID`,
                    `ISV_Value`
                ) VALUES (
                    :settingId,
                    :value
                );";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":settingId"=>$settingId,
            ":value"=>$value
        ]);
        return $do->rowCount() ? true : false;
    }
}
