<?php

namespace dhope0000\LXDClient\Model\Deployments\CloudConfig;

use dhope0000\LXDClient\Model\Database\Database;

class AddCloudConfig
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function add(int $deploymentId, int $cloudConfigRevId)
    {
        $sql = "INSERT INTO `Deployment_Cloud_Config`
                (
                    `DCC_Deployment_ID`,
                    `DCC_Cloud_Config_Rev_ID`
                ) VALUES (
                    :deploymentId,
                    :cloudConfigRevId
                );
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":deploymentId"=>$deploymentId,
            ":cloudConfigRevId"=>$cloudConfigRevId
        ]);
        return $do->rowCount() ? true : false;
    }
}
