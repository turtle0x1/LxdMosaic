<?php

namespace dhope0000\LXDClient\Model\Deployments;

use dhope0000\LXDClient\Model\Database\Database;

class InsertDeployment
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(string $name) :bool
    {
        $sql = "INSERT INTO `Deployments`
                (
                    `Deployment_Name`
                ) VALUES (
                    :deploymentName
                )";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":deploymentName"=>$name
        ]);
        return $do->rowCount() ? true : false;
    }

    public function getDeploymentId()
    {
        return $this->database->lastInsertId();
    }
}
