<?php
namespace dhope0000\LXDClient\Model\CloudConfig\Data;

use dhope0000\LXDClient\Model\Database\Database;

class Update
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(int $cloudConfigId, string $codeJson, string $imageJson)
    {
        $sql = "INSERT INTO `Cloud_Config_Data`
                (
                    `CCD_Cloud_Config_ID`,
                    `CCD_Data`,
                        `CCD_Image_Details`
                ) VALUES(
                    :cloudConfigId,
                    :codeJson,
                    :imageDetails
                )";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":cloudConfigId"=>$cloudConfigId,
            ":codeJson"=>$codeJson,
            ":imageDetails"=>$imageJson
        ]);
        return $do->rowCount() ? true : false;
    }
}
