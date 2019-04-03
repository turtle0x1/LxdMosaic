<?php
namespace dhope0000\LXDClient\Model\Hosts;

use dhope0000\LXDClient\Model\Database\Database;

class HostList
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function getHostList()
    {
        $sql = "SELECT
                    `Host_Url_And_Port`
                FROM
                    `Hosts`
                ORDER BY
                    `Host_ID` DESC
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_COLUMN, 0);
    }

    public function haveAny()
    {
        return (bool) count($this->getHostList());
    }

    public function getHostListWithDetails()
    {
        $sql = "SELECT
                    `Host_ID`,
                    `Host_Url_And_Port`,
                    `Host_Alias`
                FROM
                    `Hosts`
                ORDER BY
                    `Host_ID` DESC
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
