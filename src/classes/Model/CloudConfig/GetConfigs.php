<?php
namespace dhope0000\LXDClient\Model\CloudConfig;

use dhope0000\LXDClient\Model\Database\Database;

class GetConfigs
{
    private \PDO $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function getAll()
    {
        $sql = "SELECT
                    `CC_ID` as `id`,
                    `CC_Name` as `name`,
                    `CC_Namespace` as `namespace`
                FROM
                    `Cloud_Config`";
        $do = $this->database->query($sql);
        $results = $do->fetchAll(\PDO::FETCH_ASSOC);
        //TODO move this to a service
        $output = [];
        foreach ($results as $result) {
            if (!isset($output[$result["namespace"]])) {
                $output[$result["namespace"]] = [];
            }
            $output[$result["namespace"]][] = $result;
        }
        return $output;
    }
}
