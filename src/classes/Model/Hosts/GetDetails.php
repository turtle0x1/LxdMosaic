<?php
namespace dhope0000\LXDClient\Model\Hosts;

use dhope0000\LXDClient\Model\Database\Database;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Client\LxdClient;
use \DI\Container;

class GetDetails
{
    public function __construct(Database $database, Container $container)
    {
        $this->database = $database->dbObject;
        $this->container = $container;
    }

    public function getIpAndAlias(int $hostId)
    {
        $sql = "SELECT
                    `Host_Url_And_Port` as `urlAndPort`,
                    COALESCE(`Host_Alias`, `Host_Url_And_Port`) as `alias`
                FROM
                    `Hosts`
                WHERE
                    `Host_ID` = :hostId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchAlias(int $hostId)
    {
        $sql = "SELECT
                    COALESCE(`Host_Alias`, `Host_Url_And_Port`)
                FROM
                    `Hosts`
                WHERE
                    `Host_ID` = :hostId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId
        ]);
        return $do->fetchColumn();
    }

    public function fetchHost($hostId)
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
                    `Host_ID` = :hostId
                ORDER BY
                    `Host_ID` DESC
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId
        ]);
        return $do->fetchObject("dhope0000\LXDClient\Objects\Host", [$this->container->get("dhope0000\LXDClient\Model\Client\LxdClient")]);
    }

    public function getIdByUrlMatch($hostUrl)
    {
        $sql = "SELECT
                    `Host_ID`
                FROM
                    `Hosts`
                WHERE
                    `Host_Url_And_Port` LIKE ?
                ORDER BY
                    `Host_ID` DESC
                ";
        $do = $this->database->prepare($sql);
        $do->execute(
            ["%$hostUrl%"]
        );
        return $do->fetchColumn();
    }

    public function getCertificate($hostId)
    {
        $sql = "SELECT
                    `Host_Cert_Path`
                FROM
                    `Hosts`
                WHERE
                    `Host_ID` = :hostId
                ORDER BY
                    `Host_ID` DESC
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId
        ]);
        return $do->fetchColumn();
    }
}
