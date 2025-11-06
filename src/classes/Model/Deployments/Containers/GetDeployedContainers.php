<?php

namespace dhope0000\LXDClient\Model\Deployments\Containers;

use dhope0000\LXDClient\Model\Database\Database;

class GetDeployedContainers
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function getByDeploymentId(int $deploymentId)
    {
        $sql = 'SELECT
                    `DC_ID` as `dcId`,
                    `DC_Host_ID` as `hostId`,
                    `DC_Name` as `name`,
                    `DC_First_Start` as `hasBeenSeenStarted`,
                    `DC_Last_Start` as `lastStart`,
                    `DC_Phone_Home_Date` as `phoneHomeDate`,
                    `DC_Destoryed` as `containerDestoryed`
                FROM
                    `Deployment_Containers`
                WHERE
                    `DC_Deployment_ID` = :deploymentId
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':deploymentId' => $deploymentId,
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
