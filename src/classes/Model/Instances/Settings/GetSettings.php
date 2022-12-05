<?php

namespace dhope0000\LXDClient\Model\Instances\Settings;

use dhope0000\LXDClient\Model\Database\Database;

class GetSettings
{
    private \PDO $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function getAllEnabledNamesAndDefaults()
    {
        $sql = "SELECT
                    `CO_Key` as `key`,
                    `CO_Default` as `value`,
                    `CO_Description` as `description`
                FROM
                    `Container_Options`
                WHERE
                    `CO_Enabled` = 1;
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
