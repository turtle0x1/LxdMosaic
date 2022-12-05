<?php

namespace dhope0000\LXDClient\Model\Deployments\CloudConfig;

use dhope0000\LXDClient\Model\Database\Database;

class FetchCloudConfigs
{
    private \PDO $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function getAll(int $deploymentId)
    {
        $sql = "SELECT
                    `DCC_Cloud_Config_Rev_ID` as `revId`,
                    `CC_ID` as `id`,
                    `CC_Name` as `name`,
                    `CC_Namespace` as `namespace`
                FROM
                    `Deployment_Cloud_Config`
                INNER JOIN `Cloud_Config_Data` ON
                    `Cloud_Config_Data`.`CCD_ID` = `Deployment_Cloud_Config`.`DCC_Cloud_Config_Rev_ID`
                INNER JOIN `Cloud_Config` ON
                    `Cloud_Config_Data`.`CCD_Cloud_Config_ID` = `Cloud_Config`.`CC_ID`
                WHERE
                    `DCC_Deployment_ID` = :deploymentId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":deploymentId"=>$deploymentId
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
