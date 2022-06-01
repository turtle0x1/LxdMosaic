<?php

namespace dhope0000\LXDClient\Model\ProjectAnalytics;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteAnalytics
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function deleteBefore(\DateTimeInterface $before)
    {
        $sql = "DELETE FROM
                    `Project_Analytics`
                WHERE
                    `PA_Date_Created` <= :beforeDate
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":beforeDate"=>$before->format("Y-m-d H:i:s")
        ]);
        return $do->rowCount() ? true : false;
    }

    public function deleteForHost(int $hostId)
    {
        $sql = "DELETE FROM
                    `Project_Analytics`
                WHERE
                    `PA_Host_ID` <= :hostId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId
        ]);
        return $do->rowCount() ? true : false;
    }
}
