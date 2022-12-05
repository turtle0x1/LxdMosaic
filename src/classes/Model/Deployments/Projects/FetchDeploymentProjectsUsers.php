<?php

namespace dhope0000\LXDClient\Model\Deployments\Projects;

use dhope0000\LXDClient\Model\Database\Database;

class FetchDeploymentProjectsUsers
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function userHasAccess(int $userId, int $deploymentId) :bool
    {
        $sql = "SELECT
                    1
                FROM
                    `Users`
                LEFT JOIN `User_Allowed_Projects` ON
                    `User_Allowed_Projects`.`UAP_User_ID` = `User_ID`
                LEFT JOIN `Deployment_Projects` ON
                    `DP_Deployment_ID` = :deploymentId AND
                    `DP_Host_ID` = `User_Allowed_Projects`.`UAP_Host_ID` AND
                    `DP_Project` = `User_Allowed_Projects`.`UAP_Project`
                WHERE
                    `User_ID` = :userId
                AND
                    (
                        `Deployment_Projects`.`DP_Project` IS NOT NULL
                        OR
                        `User_Admin` = 1
                    )
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":deploymentId"=>$deploymentId,
            ":userId"=>$userId
        ]);
        return $do->fetchColumn() ? true : false;
    }
}
