<?php

namespace dhope0000\LXDClient\Model\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Model\Database\Database;

class InsertActionLog
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(string $controller, string $params)
    {
        $sql = "INSERT INTO `Recorded_Actions`
                (
                    `RA_Controller`,
                    `RA_Params`
                ) VALUES (
                    :controller,
                    :params
                );";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":controller"=>$controller,
            ":params"=>$params
        ]);
        return $do->rowCount() ? true : false;
    }
}
