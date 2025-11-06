<?php

namespace dhope0000\LXDClient\Model\Hosts;

use dhope0000\LXDClient\Model\Database\Database;

class AddHost
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function addHost($urlAndPort, $keyPath, $certPath, $combiendPath, $alias = null, $socketPath = '')
    {
        $sql = 'INSERT INTO `Hosts`
                (
                    `Host_Url_And_Port`,
                    `Host_Key_File`,
                    `Host_Cert_Only_File`,
                    `Host_Cert_Path`,
                    `Host_Alias`,
                    `Host_Socket_Path`
                ) VALUES (
                    :urlAndPort,
                    :keyPath,
                    :certPath,
                    :combinedPath,
                    :alias,
                    :socketPath
                );
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':urlAndPort' => $urlAndPort,
            ':keyPath' => $keyPath,
            ':certPath' => $certPath,
            ':combinedPath' => $combiendPath,
            ':alias' => $alias,
            ':socketPath' => $socketPath,
        ]);
        return $do->rowCount() ? true : false;
    }

    public function getId(): int
    {
        return $this->database->lastInsertId();
    }
}
