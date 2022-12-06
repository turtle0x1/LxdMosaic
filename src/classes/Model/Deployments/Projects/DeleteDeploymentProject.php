<?php

namespace dhope0000\LXDClient\Model\Deployments\Projects;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteDeploymentProject
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function delete(int $deploymentProjectId) :bool
    {
        $sql = "DELETE FROM `Deployment_Projects` WHERE `DP_ID` = :deploymentProjectId";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":deploymentProjectId"=>$deploymentProjectId
        ]);
        return $do->rowCount() ? true : false;
    }
}
