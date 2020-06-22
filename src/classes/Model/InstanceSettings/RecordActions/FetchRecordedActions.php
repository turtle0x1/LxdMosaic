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
                    `RA_Params` as `params`
                FROM
                    `Recorded_Actions`
                ORDER BY `RA_ID` DESC
                LIMIT :ammount
                ";
        $do = $this->database->prepare($sql);
        $do->bindValue(":ammount", (int) $ammount, \PDO::PARAM_INT);
        $do->execute();
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchForHostInstance(int $hostId, string $instance)
    {
        $sql = "SELECT
                    *
                FROM
                    `Recorded_Actions`
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
        $do = $this->db->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":instance"=>$instance
        ]);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
