<?php

namespace dhope0000\LXDClient\Model\Deployments\Projects;

use dhope0000\LXDClient\Model\Database\Database;

class FetchDeploymentProjects
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll(int $deploymentId): array
    {
        $sql = 'SELECT
                    `DP_ID` as `id`,
                    COALESCE(`Host_Alias`, `Host_Url_And_Port`) as `hostAlias`,
                    `Host_ID` as `hostId`,
                    `DP_Project` as `project`
                FROM
                    `Deployment_Projects`
                LEFT JOIN `Hosts` ON
                    `Hosts`.`Host_ID` = `DP_Host_ID`
                WHERE
                    `DP_Deployment_ID` = :deploymentId
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':deploymentId' => $deploymentId,
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
