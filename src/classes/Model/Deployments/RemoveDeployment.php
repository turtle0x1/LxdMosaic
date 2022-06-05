<?php

namespace dhope0000\LXDClient\Model\Deployments;

use dhope0000\LXDClient\Model\Database\Database;

class RemoveDeployment
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function delete(int $deploymentId)
    {
        $sql = "DELETE FROM
                    `Deployments`
                WHERE
                    `Deployment_ID` = :deploymentId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":deploymentId"=>$deploymentId
        ]);
        return $do->rowCount() ? true : false;
    }
}
