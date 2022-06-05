<?php
namespace dhope0000\LXDClient\Model\CloudConfig;

use dhope0000\LXDClient\Model\Database\Database;

class CreateConfig
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function create(string $name, string $namespace, $description)
    {
        $sql = "INSERT INTO `Cloud_Config`
                (
                    `CC_Name`,
                    `CC_Namespace`,
                    `CC_Description`
                ) VALUES (
                    :name,
                    :namespace,
                    :description
                )
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":name"=>$name,
            ":namespace"=>$namespace,
            ":description"=>$description
        ]);
        return $do->rowCount() ? true : false;
    }
}
