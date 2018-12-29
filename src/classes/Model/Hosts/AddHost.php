<?php

namespace dhope0000\LXDClient\Model\Hosts;

use dhope0000\LXDClient\Model\Database\Database;

class AddHost
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function addHost($urlAndPort, $certPath)
    {
        $sql = "INSERT INTO `Hosts`
                (
                    `Host_Url_And_Port`,
                    `Host_Cert_Path`
                ) VALUES (
                    :urlAndPort,
                    :certPath
                );
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":urlAndPort"=>$urlAndPort,
            ":certPath"=>$certPath
        ]);
        return $do->rowCount() ? true : false;
    }
}
