<?php

namespace dhope0000\LXDClient\Model\Deployments\Containers;

use dhope0000\LXDClient\Model\Database\Database;

class UpdateStartTimes
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function updateFirstStart(int $deploymentId, int $hostId, string  $name)
    {
        $sql = "UPDATE
                    `Deployment_Containers`
                SET
                    `DC_First_Start` = CURRENT_TIMESTAMP
                WHERE
                    `DC_Host_ID` = :hostId
                AND
                    `DC_Deployment_ID` = :deploymentId
                AND
                    `DC_Name` = :name
                AND
                    `DC_First_Start` IS NULL -- Do this to prevent adding new dates
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":deploymentId"=>$deploymentId,
            ":name"=>$name
        ]);
        return $do->rowCount() ? true : false;
    }

    public function updateLastStart(int $deploymentId, int $hostId, string  $name)
    {
        $sql = "UPDATE
                    `Deployment_Containers`
                SET
                    `DC_Last_Start` = CURRENT_TIMESTAMP
                WHERE
                    `DC_Host_ID` = :hostId
                AND
                    `DC_Deployment_ID` = :deploymentId
                AND
                    `DC_Name` = :name
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":deploymentId"=>$deploymentId,
            ":name"=>$name
        ]);
        return $do->rowCount() ? true : false;
    }
}
