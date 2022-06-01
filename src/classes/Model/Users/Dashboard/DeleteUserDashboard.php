<?php

namespace dhope0000\LXDClient\Model\Users\Dashboard;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteUserDashboard
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function delete(int $dashboardId)
    {
        $sql = "DELETE FROM
                    `User_Dashboards`
                WHERE
                    `UD_ID` = :dashboardId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":dashboardId"=>$dashboardId
        ]);
        return $do->rowCount() ? true : false;
    }
}
