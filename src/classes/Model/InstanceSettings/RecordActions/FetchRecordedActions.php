<?php

namespace dhope0000\LXDClient\Model\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Model\Database\Database;

class FetchRecordedActions
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetch(int $ammount)
    {
        $sql = "SELECT
                    `RA_Date_Created` as `date`,
                    `RA_Controller` as `controller`,
                    `RA_Params` as `params`,
                    COALESCE(`Users`.`User_Name`, 'To Old To Know') as `userName`
                FROM
                    `Recorded_Actions`
                LEFT JOIN `Users` ON
                    `Recorded_Actions`.`RA_User_ID` = `Users`.`User_ID`
                ORDER BY `RA_ID` DESC
                LIMIT :ammount
                ";
        $do = $this->database->prepare($sql);
        $do->bindValue(":ammount", (int) $ammount, \PDO::PARAM_INT);
        $do->execute();
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchUserActions(int $userId, string $controller, int $ammount)
    {
        $sql = "SELECT
                    `RA_Date_Created` as `date`,
                    `RA_Controller` as `controller`,
                    `RA_Params` as `params`,
                    COALESCE(`Users`.`User_Name`, 'To Old To Know') as `userName`
                FROM
                    `Recorded_Actions`
                LEFT JOIN `Users` ON
                    `Recorded_Actions`.`RA_User_ID` = `Users`.`User_ID`
                WHERE
                    `RA_Controller` = :controller
                AND
                    `RA_User_ID` = :userId
                ORDER BY `RA_ID` DESC
                LIMIT :ammount
                ";
        $do = $this->database->prepare($sql);
        $do->bindValue(":controller", $controller, \PDO::PARAM_STR);
        $do->bindValue(":userId", (int) $userId, \PDO::PARAM_INT);
        $do->bindValue(":ammount", (int) $ammount, \PDO::PARAM_INT);
        $do->execute();
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchUserActionsForControllers(int $userId, array $controllers)
    {
        $qMarks = join(',', array_fill(0, count($controllers), '?'));
        $sql = "SELECT
                    `RA_Controller`,
                    `RA_Date_Created` as `date`,
                    `RA_Params` as `params`,
                    COALESCE(`Users`.`User_Name`, 'To Old To Know') as `userName`
                FROM
                    `Recorded_Actions`
                LEFT JOIN `Users` ON
                    `Recorded_Actions`.`RA_User_ID` = `Users`.`User_ID`
                WHERE
                    `RA_User_ID` = ?
                AND
                    `RA_Controller` IN ($qMarks)
                ORDER BY
                    `RA_ID` DESC
                ";
        $do = $this->database->prepare($sql);
        $params = array_merge([$userId], $controllers);
        $do->execute($params);
        return $do->fetchAll(\PDO::FETCH_GROUP|\PDO::FETCH_ASSOC);
    }

    public function fetchForHostInstance(int $hostId, string $instance)
    {
        $sql = "SELECT
                    `RA_Date_Created` as `date`,
                    `RA_Controller` as `controller`,
                    `RA_Params` as `params`,
                    COALESCE(`Users`.`User_Name`, 'To Old To Know') as `userName`
                FROM
                    `Recorded_Actions`
                LEFT JOIN `Users` ON
                    (
                        `Recorded_Actions`.`RA_User_ID` = `Users`.`User_ID`
                        OR
                        JSON_EXTRACT(`RA_Params`, '$.userId') = `Users`.`User_ID`
                    )
                WHERE
                    (
                        JSON_EXTRACT(`RA_Params`, '$.container') = :instance
                        OR
                        JSON_EXTRACT(`RA_Params`, '$.instance') = :instance
                    )
                AND
                    (
                        JSON_EXTRACT(`RA_Params`, '$.host.hostId') = :hostId
                        OR
                        JSON_EXTRACT(`RA_Params`, '$.hostId') = :hostId
                    )
                ORDER BY `Recorded_Actions`.`RA_Date_Created`  DESC";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":instance"=>$instance
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
