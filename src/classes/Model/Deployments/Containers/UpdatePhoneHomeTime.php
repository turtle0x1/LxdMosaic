<?php

namespace dhope0000\LXDClient\Model\Deployments\Containers;

use dhope0000\LXDClient\Model\Database\Database;

class UpdatePhoneHomeTime
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function update(int $deploymentId, string $name)
    {
        $sql = 'UPDATE
                    `Deployment_Containers`
                SET
                    `DC_Phone_Home_Date` = CURRENT_TIMESTAMP
                WHERE
                    `DC_Deployment_ID` = :deploymentId
                AND
                    `DC_Name` = :name
                AND
                    `DC_Phone_Home_Date` IS NULL -- Do this to prevent adding new dates
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':deploymentId' => $deploymentId,
            ':name' => $name,
        ]);
        return $do->rowCount() ? true : false;
    }
}
