<?php

namespace dhope0000\LXDClient\Model\Users\Dashboard\Graphs;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteDashboardGraph
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function delete(int $graphId) :bool
    {
        $sql = "DELETE FROM
                    `User_Dashboard_Graphs`
                WHERE
                    `UDG_ID` = :graphId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":graphId"=>$graphId
        ]);
        return $do->rowCount() ? true : false;
    }
}
