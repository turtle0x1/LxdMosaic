<?php
namespace dhope0000\LXDClient\Model\Hosts;

use dhope0000\LXDClient\Model\Database\Database;
use \DI\Container;
use dhope0000\LXDClient\Objects\HostsCollection;

class HostList
{
    public function __construct(Database $database, Container $container)
    {
        $this->database = $database->dbObject;
        $this->container = $container;
    }

    public function getHostCollection(array $hostIds)
    {
        $qMarks = join(',', array_fill(0, count($hostIds), '?'));
        $sql = "SELECT
                    `Host_ID` as `id`,
                    `Host_Url_And_Port` as `urlAndPort`,
                    `Host_Cert_Path` as `certPath`,
                    `Host_Cert_Only_File` as `certFilePath`,
                    `Host_Key_File` as `keyFilePath`,
                    COALESCE(`Host_Alias`, `Host_Url_And_Port`) as `alias`,
                    `Host_Online` as `hostOnline`
                FROM
                    `Hosts`
                WHERE
                    `Host_ID` IN ($qMarks)
                ORDER BY
                    `Host_ID` DESC
                ";
        $do = $this->database->prepare($sql);
        $do->execute($hostIds);
        return new HostsCollection($do->fetchAll(\PDO::FETCH_CLASS, "dhope0000\LXDClient\Objects\Host", [$this->container->get("dhope0000\LXDClient\Model\Client\LxdClient")]));
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
                    COALESCE(`Host_Alias`, `Host_Url_And_Port`) as `Host_Alias`,
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
                    `Host_ID` as `id`,
                    `Host_Url_And_Port` as `urlAndPort`,
                    `Host_Cert_Path` as `certPath`,
                    `Host_Cert_Only_File` as `certFilePath`,
                    `Host_Key_File` as `keyFilePath`,
                    COALESCE(`Host_Alias`, `Host_Url_And_Port`) as `alias`,
                    `Host_Online` as `hostOnline`
                FROM
                    `Hosts`
                WHERE
                    `Host_Online` = 1
                ORDER BY
                    `Host_ID` ASC
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_CLASS, "dhope0000\LXDClient\Objects\Host", [$this->container->get("dhope0000\LXDClient\Model\Client\LxdClient")]);
    }

    public function fetchHostsNotInList(array $hostIds)
    {
        if (empty($hostIds)) {
            return [];
        }

        $qMarks = join(',', array_fill(0, count($hostIds), '?'));
        $sql = "SELECT
                    `Host_ID` as `id`,
                    `Host_Url_And_Port` as `urlAndPort`,
                    `Host_Cert_Path` as `certPath`,
                    `Host_Cert_Only_File` as `certFilePath`,
                    `Host_Key_File` as `keyFilePath`,
                    COALESCE(`Host_Alias`, `Host_Url_And_Port`) as `alias`,
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
        return $do->fetchAll(\PDO::FETCH_CLASS, "dhope0000\LXDClient\Objects\Host", [$this->container->get("dhope0000\LXDClient\Model\Client\LxdClient")]);
    }

    public function fetchAllHosts()
    {
        $sql = "SELECT
                    `Host_ID` as `id`,
                    `Host_Url_And_Port` as `urlAndPort`,
                    `Host_Cert_Path` as `certPath`,
                    `Host_Cert_Only_File` as `certFilePath`,
                    `Host_Key_File` as `keyFilePath`,
                    COALESCE(`Host_Alias`, `Host_Url_And_Port`) as `alias`,
                    `Host_Online` as `hostOnline`
                FROM
                    `Hosts`
                ORDER BY
                    `Host_ID` DESC
                ";
        $do = $this->database->query($sql);
        return $do->fetchAll(\PDO::FETCH_CLASS, "dhope0000\LXDClient\Objects\Host", [$this->container->get("dhope0000\LXDClient\Model\Client\LxdClient")]);
    }
}
