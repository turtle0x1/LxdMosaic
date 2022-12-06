<?php
namespace dhope0000\LXDClient\Model\CloudConfig\Data;

use dhope0000\LXDClient\Model\Database\Database;

class Update
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(
        int $cloudConfigId,
        string $codeJson,
        string $imageJson,
        string $envVariablesJson
    ) :bool {
        $sql = "INSERT INTO `Cloud_Config_Data`
                (
                    `CCD_Cloud_Config_ID`,
                    `CCD_Data`,
                    `CCD_Image_Details`,
                    `CCD_Enviroment_Variables`
                ) VALUES(
                    :cloudConfigId,
                    :codeJson,
                    :imageDetails,
                    :envVariables
                )";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":cloudConfigId"=>$cloudConfigId,
            ":codeJson"=>$codeJson,
            ":imageDetails"=>$imageJson,
            ":envVariables"=>$envVariablesJson
        ]);
        return $do->rowCount() ? true : false;
    }
}
