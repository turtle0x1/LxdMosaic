<?php

namespace dhope0000\LXDClient\Model\CloudConfig\Namespaces;

use dhope0000\LXDClient\Model\Database\Database;

class GetCloudConfigs
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function haveCloudConfigInNamespace(string $name, string $namespace)
    {
        $sql = "SELECT
                    1
                FROM
                    `Cloud_Config`
                WHERE
                    `CC_Name` = :name
                AND
                    `CC_Namespace` = :namespace";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":name"=>$name,
            ":namespace"=>$namespace
        ]);
        return $do->rowCount() ? true : false;
    }
}
