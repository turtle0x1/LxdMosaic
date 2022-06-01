<?php

namespace dhope0000\LXDClient\Model\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Model\Database\Database;

class InsertActionLog
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(int $userId, string $controller, string $params)
    {
        $sql = "INSERT INTO `Recorded_Actions`
                (
                    `RA_User_ID`,
                    `RA_Controller`,
                    `RA_Params`
                ) VALUES (
                    :userId,
                    :controller,
                    :params
                );";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":controller"=>$controller,
            ":params"=>$params
        ]);
        return $do->rowCount() ? true : false;
    }
}
