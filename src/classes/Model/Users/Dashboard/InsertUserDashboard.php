<?php

namespace dhope0000\LXDClient\Model\Users\Dashboard;

use dhope0000\LXDClient\Model\Database\Database;

class InsertUserDashboard
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(int $userId, string $name)
    {
        $sql = "INSERT INTO `User_Dashboards`
                (
                    `UD_User_ID`,
                    `UD_Name`
                ) VALUES (
                    :userId,
                    :name
                )";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":name"=>$name
        ]);
        return $do->rowCount() ? true : false;
    }
}
