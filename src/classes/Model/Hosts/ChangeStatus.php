<?php

namespace dhope0000\LXDClient\Model\Hosts;

use dhope0000\LXDClient\Model\Database\Database;

class ChangeStatus
{
    private $db;

    public function __construct(Database $database)
    {
        $this->db = $database->dbObject;
    }

    public function setOnline(int $hostId) :bool
    {
        $sql = "UPDATE `Hosts` SET `Host_Online` = 1 WHERE `Host_ID` = :hostId";
        $do = $this->db->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId
        ]);
        return $do->rowCount() ? true : false;
    }

    public function setOffline(int $hostId) :bool
    {
        $sql = "UPDATE `Hosts` SET `Host_Online` = 0 WHERE `Host_ID` = :hostId";
        $do = $this->db->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId
        ]);
        return $do->rowCount() ? true : false;
    }
}
