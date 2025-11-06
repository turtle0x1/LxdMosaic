<?php

namespace dhope0000\LXDClient\Model\InstanceSettings\ImageServers;

use dhope0000\LXDClient\Model\Database\Database;

class InsertImageServer
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(string $alias, string $url, int $protocol)
    {
        $sql = 'INSERT INTO `Image_Servers` (
                    `IS_Alias`,
                    `IS_Url`,
                    `IS_Protocol`
                ) VALUES (
                    :alias,
                    :url,
                    :protocol
                );';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':alias' => $alias,
            ':url' => $url,
            ':protocol' => $protocol,
        ]);
        return $do->rowCount() ? true : false;
    }
}
