<?php

namespace dhope0000\LXDClient\Model\Hosts;

use dhope0000\LXDClient\Model\Database\Database;

class AddHost
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function addHost($urlAndPort, $keyPath, $certPath, $combiendPath)
    {
        $sql = "INSERT INTO `Hosts`
                (
                    `Host_Url_And_Port`,
                    `Host_Key_File`,
                    `Host_Cert_Only_File`,
                    `Host_Cert_Path`
                ) VALUES (
                    :urlAndPort,
                    :keyPath,
                    :certPath,
                    :combinedPath
                );
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":urlAndPort"=>$urlAndPort,
            ":keyPath"=>$keyPath,
            ":certPath"=>$certPath,
            ":combinedPath"=>$combiendPath
        ]);
        return $do->rowCount() ? true : false;
    }
}
