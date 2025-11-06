<?php

namespace dhope0000\LXDClient\Model\Deployments;

use dhope0000\LXDClient\Model\Database\Database;

class FetchDeployments
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll()
    {
        $sql = 'SELECT
                    `Deployment_ID` as `id`,
                    `Deployment_Name` as `name`
                FROM
                    `Deployments`
                ORDER BY
                    `Deployment_ID` DESC
                ';
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchUserHasAccessTo(int $userId)
    {
        $sql = 'SELECT DISTINCT
                    `Deployment_ID` as `id`,
                    `Deployment_Name` as `name`
                FROM
                    `User_Allowed_Projects`
                LEFT JOIN  `Deployment_Projects` ON
                    `Deployment_Projects`.`DP_Host_ID` = `User_Allowed_Projects`.`UAP_Host_ID`
                    AND
                    `Deployment_Projects`.`DP_Project` = `User_Allowed_Projects`.`UAP_Project`
                LEFT JOIN  `Deployments` ON
                    `Deployments`.`Deployment_ID` = `Deployment_Projects`.`DP_Deployment_ID`
                WHERE
                    `User_Allowed_Projects`.`UAP_User_ID` = :userId
                AND
                    `Deployment_Projects`.`DP_Host_ID` IS NOT NULL
                ORDER BY
                    `Deployment_ID` DESC
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':userId' => $userId,
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetch(int $deploymentId)
    {
        $sql = 'SELECT
                    `Deployment_ID` as `id`,
                    `Deployment_Name` as `name`
                FROM
                    `Deployments`
                WHERE
                    `Deployment_ID` = :id
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':id' => $deploymentId,
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }

    public function byHostContainer(int $hostId, string $container)
    {
        $sql = 'SELECT
                    `Deployment_ID` as `id`,
                    `Deployment_Name` as `name`
                FROM
                    `Deployments`
                WHERE
                    `Deployment_ID` = (
                        SELECT
                            `DC_Deployment_ID`
                        FROM
                            `Deployment_Containers`
                        WHERE
                            `DC_Host_ID` = :hostId
                        AND
                            `DC_Name` = :container
                    )
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':hostId' => $hostId,
            ':container' => $container,
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }
}
