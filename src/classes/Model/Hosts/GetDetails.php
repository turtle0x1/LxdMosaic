<?php

namespace dhope0000\LXDClient\Model\Hosts;

use dhope0000\LXDClient\Model\Database\Database;
use DI\Container;

class GetDetails
{
    private $database;

    public function __construct(
        Database $database,
        private readonly Container $container
    ) {
        $this->database = $database->dbObject;
    }

    public function getIdByUrlMatch(string $hostUrl)
    {
        $sql = 'SELECT
                    `Host_ID`
                FROM
                    `Hosts`
                WHERE
                    `Host_Url_And_Port` = :hostUrl
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':hostUrl' => $hostUrl,
        ]);
        return $do->fetchColumn();
    }

    public function fetchAlias(int $hostId)
    {
        $sql = 'SELECT
                    COALESCE(`Host_Alias`, `Host_Url_And_Port`)
                FROM
                    `Hosts`
                WHERE
                    `Host_ID` = :hostId
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':hostId' => $hostId,
        ]);
        return $do->fetchColumn();
    }

    public function fetchAliases(array $hostIds)
    {
        $qMarks = join(',', array_fill(0, count($hostIds), '?'));
        $sql = "SELECT
                    `Host_ID`,
                    COALESCE(`Host_Alias`, `Host_Url_And_Port`)
                FROM
                    `Hosts`
                WHERE
                    `Host_ID` IN ({$qMarks})
                ";
        $do = $this->database->prepare($sql);
        $do->execute($hostIds);
        return $do->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function fetchHost($hostId)
    {
        $sql = 'SELECT
                    `Host_ID` as `id`,
                    `Host_Url_And_Port` as `urlAndPort`,
                    `Host_Cert_Path` as `certPath`,
                    `Host_Cert_Only_File` as `certFilePath`,
                    `Host_Key_File` as `keyFilePath`,
                    COALESCE(`Host_Alias`, `Host_Url_And_Port`) as `alias`,
                    `Host_Online` as `hostOnline`,
                    `Host_Support_Load_Averages` as `supportsLoadAvgs`
                FROM
                    `Hosts`
                WHERE
                    `Host_ID` = :hostId
                ORDER BY
                    `Host_ID` DESC
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':hostId' => $hostId,
        ]);
        return $do->fetchObject(
            "dhope0000\LXDClient\Objects\Host",
            [$this->container->get("dhope0000\LXDClient\Model\Client\LxdClient")]
        );
    }

    public function fetchHostByUrl($hostUrl)
    {
        $sql = 'SELECT
                    `Host_ID` as `id`,
                    `Host_Url_And_Port` as `urlAndPort`,
                    `Host_Cert_Path` as `certPath`,
                    `Host_Cert_Only_File` as `certFilePath`,
                    `Host_Key_File` as `keyFilePath`,
                    COALESCE(`Host_Alias`, `Host_Url_And_Port`) as `alias`,
                    `Host_Online` as `hostOnline`,
                    `Host_Support_Load_Averages` as `supportsLoadAvgs`
                FROM
                    `Hosts`
                WHERE
                    `Host_Url_And_Port` LIKE ?
                ORDER BY
                    `Host_ID` DESC
                LIMIT 1
                ';
        $do = $this->database->prepare($sql);
        $do->execute(["%{$hostUrl}%"]);
        return $do->fetchObject(
            "dhope0000\LXDClient\Objects\Host",
            [$this->container->get("dhope0000\LXDClient\Model\Client\LxdClient")]
        );
    }

    public function getCertificate($hostId)
    {
        $sql = 'SELECT
                    `Host_Cert_Path`
                FROM
                    `Hosts`
                WHERE
                    `Host_ID` = :hostId
                ORDER BY
                    `Host_ID` DESC
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':hostId' => $hostId,
        ]);
        return $do->fetchColumn();
    }

    public function getSocketPath($hostId)
    {
        $sql = 'SELECT
                    `Host_Socket_Path`
                FROM
                    `Hosts`
                WHERE
                    `Host_ID` = :hostId
                ORDER BY
                    `Host_ID` DESC
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':hostId' => $hostId,
        ]);
        return $do->fetchColumn();
    }
}
