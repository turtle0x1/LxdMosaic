<?php
namespace dhope0000\LXDClient\Model\CloudConfig\Data;

use dhope0000\LXDClient\Model\Database\Database;

class Update
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(int $cloudConfigId, string $codeJson)
    {
        $sql = "INSERT INTO `Cloud_Config_Data`
                (
                    `CCD_Cloud_Config_ID`,
                    `CCD_Data`
                ) VALUES(
                    :cloudConfigId,
                    :codeJson
                )";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":cloudConfigId"=>$cloudConfigId,
            ":codeJson"=>$codeJson
        ]);
        return $do->rowCount() ? true : false;
    }
}
