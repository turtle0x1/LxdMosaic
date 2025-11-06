<?php

namespace dhope0000\LXDClient\Model\InstanceSettings;

use dhope0000\LXDClient\Model\Database\Database;

class GetSettings
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function getAllSettingsWithLatestValues()
    {
        $sql = 'SELECT
                    `IS_ID` as `settingId`,
                    `IS_Name` as `settingName`,
                    `Instance_Settings_Values`.`ISV_Date` as `currentValueDateSet`,
                    `Instance_Settings_Values`.`ISV_Value` as `currentValue`
                FROM
                    `Instance_Settings`
                LEFT JOIN `Instance_Settings_Values` ON
                    `Instance_Settings_Values`.`ISV_ID` = (
                        SELECT
                            MAX(`v1`.`ISV_ID`)
                        FROM
                            `Instance_Settings_Values` as `v1`
                        WHERE
                            `v1`.`ISV_IS_ID` = `Instance_Settings`.`IS_ID`
                    )
                ORDER BY
                    `settingId` ASC
                ';
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
