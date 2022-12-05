<?php

namespace dhope0000\LXDClient\Model\Deployments\Containers;

use dhope0000\LXDClient\Model\Database\Database;

class InsertDeploymentContainer
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(int $deploymentId, int $hostId, string $name) :bool
    {
        $sql = "INSERT INTO `Deployment_Containers` (
                    `DC_Deployment_ID`,
                    `DC_Host_ID`,
                    `DC_Name`
                ) VALUES (
                    :deploymentId,
                    :hostId,
                    :name
                )";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":deploymentId"=>$deploymentId,
            ":hostId"=>$hostId,
            ":name"=>$name
        ]);
        return $do->rowCount() ? true : false;
    }
}
