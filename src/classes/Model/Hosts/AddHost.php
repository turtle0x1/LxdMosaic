<?php

namespace dhope0000\LXDClient\Model\Hosts;

use dhope0000\LXDClient\Model\Database\Database;

class AddHost
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function addHost($urlAndPort, $keyPath, $certPath, $combiendPath, $alias = null)
    {
        $sql = "INSERT INTO `Hosts`
                (
                    `Host_Url_And_Port`,
                    `Host_Key_File`,
                    `Host_Cert_Only_File`,
                    `Host_Cert_Path`,
                    `Host_Alias`
                ) VALUES (
                    :urlAndPort,
                    :keyPath,
                    :certPath,
                    :combinedPath,
                    :alias
                );
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":urlAndPort"=>$urlAndPort,
            ":keyPath"=>$keyPath,
            ":certPath"=>$certPath,
            ":combinedPath"=>$combiendPath,
            ":alias"=>$alias
        ]);
        return $do->rowCount() ? true : false;
    }

    public function getId() :int
    {
        return $this->database->lastInsertId();
    }
}
