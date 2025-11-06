<?php

namespace dhope0000\LXDClient\Model\Deployments\Containers;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteDeploymentInstances
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function deleteForHost(int $hostId)
    {
        $sql = 'DELETE FROM
                    `Deployment_Containers`
                WHERE
                    `DC_Host_ID` = :hostId
                    ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':hostId' => $hostId,
        ]);
        return $do->rowCount() ? true : false;
    }
}
