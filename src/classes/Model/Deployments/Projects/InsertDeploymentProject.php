<?php

namespace dhope0000\LXDClient\Model\Deployments\Projects;

use dhope0000\LXDClient\Model\Database\Database;

class InsertDeploymentProject
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(int $userId, int $deploymentId, int $hostId, string $project) :bool
    {
        $sql = "INSERT INTO `Deployment_Projects` (
                    `DP_User_ID`,
                    `DP_Deployment_ID`,
                    `DP_Host_ID`,
                    `DP_Project`
                ) VALUES (
                    :userId,
                    :deploymentId,
                    :hostId,
                    :project
                );";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":deploymentId"=>$deploymentId,
            ":hostId"=>$hostId,
            ":project"=>$project
        ]);
        return $do->rowCount() ? true : false;
    }
}
