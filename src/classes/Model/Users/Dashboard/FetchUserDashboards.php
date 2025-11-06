<?php

namespace dhope0000\LXDClient\Model\Users\Dashboard;

use dhope0000\LXDClient\Model\Database\Database;

class FetchUserDashboards
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll(int $userId)
    {
        $sql = 'SELECT
                    `UD_ID` as `id`,
                    `UD_Name` as `name`
                FROM
                    `User_Dashboards`
                WHERE
                    `UD_User_ID` = :userId
                ORDER BY
                    `UD_ID` ASC
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':userId' => $userId,
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchDashboard(int $dashboardId)
    {
        $sql = 'SELECT
                    `UD_ID` as `id`,
                    `UD_User_ID` as `userId`,
                    `UD_Name` as `name`,
                    `UD_Public` as `public`
                FROM
                    `User_Dashboards`
                WHERE
                    `UD_ID` = :dashboardId
                ORDER BY
                    `UD_ID` ASC
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':dashboardId' => $dashboardId,
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }
}
