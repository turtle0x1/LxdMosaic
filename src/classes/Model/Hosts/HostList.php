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
                    `Host_Alias`,
                    `Host_Online`
                FROM
                    `Hosts`
                ORDER BY
                    `Host_ID` DESC
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOnlineHostsWithDetails()
    {
        $sql = "SELECT
                    `Host_ID`,
                    `Host_Url_And_Port`,
                    `Host_Alias`,
                    `Host_Online`
                FROM
                    `Hosts`
                WHERE
                    `Host_Online` = 1
                ORDER BY
                    `Host_ID` DESC
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchHostsNotInList(array $hostIds)
    {
        if (empty($hostIds)) {
            return [];
        }

        $qMarks = join(',', array_fill(0, count($hostIds), '?'));
        $sql = "SELECT
                    `Host_ID` as `hostId`,
                    `Host_Url_And_Port` as `urlAndPort`,
                    `Host_Alias` as `alias`,
                    `Host_Online` as `hostOnline`
                FROM
                    `Hosts`
                WHERE
                    `Host_ID` NOT IN ($qMarks)
                ORDER BY
                    `Host_ID` DESC
                ";
        $do = $this->database->prepare($sql);
        $do->execute($hostIds);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchAllHosts()
    {
        $sql = "SELECT
                    `Host_ID` as `hostId`,
                    `Host_Url_And_Port` as `urlAndPort`,
                    `Host_Alias` as `alias`,
                    `Host_Online` as `hostOnline`
                FROM
                    `Hosts`
                ORDER BY
                    `Host_ID` DESC
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_ASSOC);
    }
}
